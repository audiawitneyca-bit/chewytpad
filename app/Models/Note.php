<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // 1. Import fitur tong sampah

class Note extends Model
{
    use HasFactory, SoftDeletes; // 2. Aktifkan fitur tong sampah

    protected $guarded = []; // Biar semua kolom bisa diisi

    protected $dates = ['deleted_at']; // Khusus Laravel 8 perlu ini untuk SoftDelete

    // Relasi: Catatan ini milik Kategori apa?
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relasi: Catatan ini milik Siapa?
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}