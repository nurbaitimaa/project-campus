<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesMarketing extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_sales',
        'nama_sales',
        'email',
        'telepon'
    ];
}
