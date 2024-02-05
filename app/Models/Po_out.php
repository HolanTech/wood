<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Po_out extends Model
{
    use HasFactory;
    protected $table = 'po_outs';
    protected $guarded = [];
    public function pengrajins()
    {
        return $this->belongsTo(Pengrajin::class, 'pengrajin_id', 'id');
    }
}
