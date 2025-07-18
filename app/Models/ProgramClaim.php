<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramClaim extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_transaksi',
        'tanggal_klaim',
        'program_berjalan_id',
        'total_pembelian',
        'jumlah_unit',
        'total_klaim',
        'bukti_klaim',
        'status',
    ];

    public function programBerjalan()
    {
        return $this->belongsTo(ProgramBerjalan::class);
    }

    public function details()
    {
        return $this->hasMany(ProgramClaimDetail::class);
    }
}
