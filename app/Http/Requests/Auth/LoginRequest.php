<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate()
    {
        $this->ensureIsNotRateLimited();

        // 1. CEK APAKAH USER ADA DI TONG SAMPAH (KENA KICK)?
        // Kita cari user berdasarkan email, TERMASUK yang sudah dihapus (withTrashed)
        $kickedUser = \App\Models\User::withTrashed()
                        ->where('email', $this->input('email'))
                        ->whereNotNull('deleted_at') // Pastikan dia memang statusnya deleted
                        ->first();

        if ($kickedUser) {
            // Jika password cocok (validasi manual agar tidak bocor info akun)
            if (\Illuminate\Support\Facades\Hash::check($this->input('password'), $kickedUser->password)) {
                
                // Ambil alasan atau pesan default
                $reason = $kickedUser->ban_reason ?? 'Melanggar kebijakan aplikasi.';

                // Lempar Error agar muncul di halaman login
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'email' => "Akun Anda telah dinonaktifkan oleh Admin. Alasan: \"$reason\"",
                ]);
            }
        }

        // 2. PROSES LOGIN NORMAL (Jika user tidak di-kick)
        if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw \Illuminate\Validation\ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited()
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     *
     * @return string
     */
    public function throttleKey()
    {
        return Str::lower($this->input('email')).'|'.$this->ip();
    }
}
