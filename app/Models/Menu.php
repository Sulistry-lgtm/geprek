<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Menu extends Model
{
    use HasFactory;

    protected $table = 'menus';

    protected $fillable = [
        'store_id',
        'nama_menu',
        'harga',
        'gambar',
    ];

    // Relationships
    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }
}
