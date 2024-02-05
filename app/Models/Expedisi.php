<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expedisi extends Model
{
    use HasFactory;
    protected $table = 'expedisis';

    // Kolom yang diizinkan diisi secara massal
    protected $guarded = [];
}
