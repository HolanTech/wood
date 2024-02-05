<?php

namespace App\Models;

use App\Models\Expedisi;
use App\Models\Pelanggan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Po_in extends Model
{
    use HasFactory;
    protected $table = 'po_ins';
    protected $primaryKey = 'po_in';
    protected $guarded = [];
    public function pelanggans()
    {
        return $this->belongsTo(Pelanggan::class, 'cutomer_id', 'id');
    }
    public function expedisis()
    {
        return $this->belongsTo(Expedisi::class, 'expedisi_id', 'id');
    }
}
