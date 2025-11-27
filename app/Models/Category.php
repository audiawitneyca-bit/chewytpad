<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    // Ini biar kita nggak capek ngetik fillable satu-satu
    // Artinya: Semua kolom boleh diisi
    protected $guarded = [];

    public function notes()
    {
        return $this->hasMany(Note::class);
    }
}