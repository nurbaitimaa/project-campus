<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;

    protected $fillable = [
        'sales_marketing_id',
        'tanggal',
        'jam_masuk',
        'jam_keluar',
        'latitude',
        'longitude',
        'status',
        'keterangan',
        'updated_by',
    ];    

    public function salesMarketing()
    {
        return $this->belongsTo(SalesMarketing::class);
    }

    public function updatedBy()
{
    return $this->belongsTo(User::class, 'updated_by');
}

}
