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
    ];

    // Relasi ke master program
    public function program()
    {
        return $this->belongsTo(Program::class, 'kode_program', 'kode_program');
    }

    // Relasi ke master customer
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'kode_customer', 'kode_customer');
    }

    // Relasi ke user pembuat data
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
