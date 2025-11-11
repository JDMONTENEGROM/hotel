<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemPreference extends Model
{
    use HasFactory;

    protected $fillable = [
        'language',
        'timezone',
        'currency',
        'date_format',
        'time_format',
        'tax_percentage',
    ];

    protected $casts = [
        'tax_percentage' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Obtener las preferencias del sistema (solo debe haber una)
     */
    public static function getPreferences()
    {
        return self::first() ?? self::create([
            'language' => 'es',
            'timezone' => 'America/Bogota',
            'currency' => 'COP',
            'date_format' => 'd/m/Y',
            'time_format' => '24',
            'tax_percentage' => 19.00,
        ]);
    }

    /**
     * Obtener idiomas disponibles
     */
    public static function getAvailableLanguages()
    {
        return [
            'es' => 'Español',
            'en' => 'English',
            'pt' => 'Português',
            'fr' => 'Français',
        ];
    }

    /**
     * Obtener zonas horarias disponibles
     */
    public static function getAvailableTimezones()
    {
        return [
            'America/Bogota' => 'Bogotá (GMT-5)',
            'America/Mexico_City' => 'México (GMT-6)',
            'America/New_York' => 'Nueva York (GMT-5)',
            'America/Los_Angeles' => 'Los Ángeles (GMT-8)',
            'Europe/Madrid' => 'Madrid (GMT+1)',
            'Europe/London' => 'Londres (GMT+0)',
        ];
    }

    /**
     * Obtener monedas disponibles
     */
    public static function getAvailableCurrencies()
    {
        return [
            'COP' => 'Peso Colombiano',
            'USD' => 'Dólar Americano',
            'EUR' => 'Euro',
            'MXN' => 'Peso Mexicano',
            'BRL' => 'Real Brasileño',
        ];
    }

    /**
     * Obtener formatos de fecha disponibles
     */
    public static function getAvailableDateFormats()
    {
        return [
            'd/m/Y' => 'dd/mm/aaaa',
            'm/d/Y' => 'mm/dd/aaaa',
            'Y-m-d' => 'aaaa-mm-dd',
        ];
    }

    /**
     * Obtener formatos de hora disponibles
     */
    public static function getAvailableTimeFormats()
    {
        return [
            '24' => '24 horas',
            '12' => '12 horas (AM/PM)',
        ];
    }
}