<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'kode_program',
        'nama_program',
        'deskripsi',
        'jenis_program',
        'parameter_klaim',
        'tipe_klaim',
        // HAPUS KOLOM-KOLOM DI BAWAH INI:
        // 'min_pembelian',
        // 'reward',
        // 'reward_type',
    ];

    /**
     * Relasi ke program berjalan
     */
    public function programBerjalan()
    {
        return $this->hasMany(ProgramBerjalan::class, 'kode_program', 'kode_program');
    }
}
