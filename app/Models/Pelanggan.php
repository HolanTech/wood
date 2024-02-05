<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    use HasFactory;
    protected $table = 'pelanggans';

    // Kolom yang diizinkan diisi secara massal
    protected $fillable = [
        'nama',
        'no_hp',
        'email',
        'foto',
        'alamat',
    ];
}
