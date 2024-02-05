<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengrajin extends Model
{
    use HasFactory;

    protected $table = 'pengrajins';

    protected $guarded = [];
}
