<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramClaimDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'program_claim_id',
        'nama_outlet',
        'penjualan',
        'klaim_distributor',
        'keterangan',
    ];

    public function programClaim()
    {
        return $this->belongsTo(ProgramClaim::class);
    }
}
