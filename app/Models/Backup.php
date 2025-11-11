<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Backup extends Model
{
    use HasFactory;

    protected $fillable = [
        'filename',
        'file_path',
        'type',
        'frequency',
        'file_size',
        'user_id',
    ];

    protected $casts = [
        'file_size' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relación con el usuario que creó el backup
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obtener el tamaño del archivo formateado
     */
    public function getFormattedFileSizeAttribute()
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Obtener la URL de descarga del backup
     */
    public function getDownloadUrlAttribute()
    {
        return route('backups.download', $this->id);
    }

    /**
     * Verificar si el archivo existe
     */
    public function fileExists()
    {
        return Storage::exists($this->file_path);
    }

    /**
     * Eliminar el archivo físico
     */
    public function deleteFile()
    {
        if ($this->fileExists()) {
            Storage::delete($this->file_path);
        }
    }

    /**
     * Obtener tipos de backup disponibles
     */
    public static function getAvailableTypes()
    {
        return [
            'manual' => 'Manual',
            'automatic' => 'Automático',
        ];
    }

    /**
     * Obtener frecuencias disponibles
     */
    public static function getAvailableFrequencies()
    {
        return [
            'daily' => 'Diario',
            'weekly' => 'Semanal',
            'monthly' => 'Mensual',
        ];
    }
}