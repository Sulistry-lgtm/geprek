<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Store extends Model
{
    use HasFactory;

    protected $table = 'stores';

    protected $fillable = [
        'user_id',
        'nama_toko',
        'alamat',
        'whatsapp',
        'latitude',
        'longitude',
        'gambar',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function menus()
    {
        return $this->hasMany(Menu::class, 'store_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'store_id');
    }

    // Accessors / Helper attributes
    public function getAverageRatingAttribute()
    {
        return round($this->reviews()->avg('rating') ?? 0, 1);
    }
}
