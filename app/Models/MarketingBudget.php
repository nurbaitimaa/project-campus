<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketingBudget extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'program_id',
        'tahun_anggaran',
        'nilai_budget',
        'sisa_budget',
        'keterangan',
        'created_by',
        'updated_by'
    ];

    // Relasi
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
