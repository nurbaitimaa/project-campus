<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramBerjalan extends Model
{
    use HasFactory;

    protected $table = 'program_berjalan';

    protected $fillable = [
        'tanggal',
        'kode_customer',
        'kode_program',
        'start_date',
        'end_date',
        'target',
        'pic',
        'keterangan',
        'budget',
        'file_path',
        'status',
        'created_by',
        'nilai_klaim_per_item',
        'persen_klaim',
        'nominal_klaim',
    ];

    public function program()
    {
        return $this->belongsTo(Program::class, 'kode_program', 'kode_program');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'kode_customer', 'kode_customer');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function claims()
    {
        return $this->hasMany(ProgramClaim::class);
    }
}
