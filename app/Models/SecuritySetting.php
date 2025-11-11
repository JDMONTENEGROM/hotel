<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SecuritySetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'min_password_length',
        'require_numbers',
        'require_symbols',
        'allow_password_change',
        'two_factor_auth',
        'max_login_attempts',
        'lockout_duration',
        'log_activity',
    ];

    protected $casts = [
        'require_numbers' => 'boolean',
        'require_symbols' => 'boolean',
        'allow_password_change' => 'boolean',
        'two_factor_auth' => 'boolean',
        'log_activity' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Obtener la configuración de seguridad (solo debe haber una)
     */
    public static function getSettings()
    {
        return self::first() ?? self::create([
            'min_password_length' => 8,
            'require_numbers' => true,
            'require_symbols' => false,
            'allow_password_change' => true,
            'two_factor_auth' => false,
            'max_login_attempts' => 5,
            'lockout_duration' => 30,
            'log_activity' => true,
        ]);
    }
}