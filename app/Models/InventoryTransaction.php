<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InventoryTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'inventory_id',
        'tipe',
        'jumlah',
        'keterangan',
    ];

    // Relasi ke inventory
    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }
}
