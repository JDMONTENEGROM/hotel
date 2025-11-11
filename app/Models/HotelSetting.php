<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'hotel_name',
        'nit_ruc',
        'address',
        'phone',
        'email',
        'city',
        'country',
        'logo_path',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Obtener la configuración del hotel (solo debe haber una)
     */
    public static function getSettings()
    {
        return self::first() ?? self::create([
            'hotel_name' => 'Hotel Real',
            'nit_ruc' => '',
            'address' => '',
            'phone' => '',
            'email' => '',
            'city' => '',
            'country' => '',
            'logo_path' => null,
        ]);
    }

    /**
     * Obtener la URL del logo
     */
    public function getLogoUrlAttribute()
    {
        return $this->logo_path ? asset('storage/' . $this->logo_path) : asset('img/default/hotel-logo.png');
    }
}