<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_barang',
        'stok_awal',
        'satuan',
    ];

    // Relasi ke InventoryTransaction
    public function transactions()
    {
        return $this->hasMany(InventoryTransaction::class);
    }

    // Hitung stok akhir
    public function getStokAkhirAttribute()
    {
        $masuk = $this->transactions()->where('tipe', 'masuk')->sum('jumlah');
        $keluar = $this->transactions()->where('tipe', 'keluar')->sum('jumlah');

        return ($this->stok_awal + $masuk) - $keluar;
    }
}
