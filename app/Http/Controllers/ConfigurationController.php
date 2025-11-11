<?php

namespace App\Http\Controllers;

use App\Models\Backup;
use App\Models\HotelSetting;
use App\Models\SecuritySetting;
use App\Models\SystemPreference;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ConfigurationController extends Controller
{
    /**
     * Mostrar la página principal de configuración
     */
    public function index()
    {
        $hotelSettings = HotelSetting::getSettings();
        $systemPreferences = SystemPreference::getPreferences();
        $securitySettings = SecuritySetting::getSettings();
        $backups = Backup::with('user')->orderBy('created_at', 'desc')->paginate(10);
        $roles = Role::with('permissions')->get();
        $permissions = Permission::all();
        $users = User::with('roles')->get();

        return view('configuration.index', compact(
            'hotelSettings',
            'systemPreferences', 
            'securitySettings',
            'backups',
            'roles',
            'permissions',
            'users'
        ));
    }

    /**
     * Actualizar datos del hotel
     */
    public function updateHotelSettings(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'hotel_name' => 'required|string|max:255',
            'nit_ruc' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $hotelSettings = HotelSetting::getSettings();
            
            // Manejar subida de logo
            if ($request->hasFile('logo')) {
                $logoPath = $request->file('logo')->store('hotel-logos', 'public');
                $hotelSettings->logo_path = $logoPath;
            }

            $hotelSettings->update($request->except('logo'));

            return response()->json([
                'success' => true,
                'message' => 'Datos del hotel actualizados correctamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar los datos del hotel: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualizar preferencias del sistema
     */
    public function updateSystemPreferences(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'language' => 'required|string|in:es,en,pt,fr',
            'timezone' => 'required|string',
            'currency' => 'required|string|in:COP,USD,EUR,MXN,BRL',
            'date_format' => 'required|string|in:d/m/Y,m/d/Y,Y-m-d',
            'time_format' => 'required|string|in:24,12',
            'tax_percentage' => 'required|numeric|min:0|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $preferences = SystemPreference::getPreferences();
            $preferences->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Preferencias del sistema actualizadas correctamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar las preferencias: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualizar configuración de seguridad
     */
    public function updateSecuritySettings(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'min_password_length' => 'required|integer|min:6|max:20',
            'require_numbers' => 'boolean',
            'require_symbols' => 'boolean',
            'allow_password_change' => 'boolean',
            'two_factor_auth' => 'boolean',
            'max_login_attempts' => 'required|integer|min:3|max:10',
            'lockout_duration' => 'required|integer|min:5|max:120',
            'log_activity' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $securitySettings = SecuritySetting::getSettings();
            $securitySettings->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Configuración de seguridad actualizada correctamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la configuración de seguridad: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Crear copia de seguridad manual
     */
    public function createBackup(Request $request)
    {
        try {
            $filename = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
            $filePath = 'backups/' . $filename;
            
            // Crear el directorio si no existe
            if (!Storage::exists('backups')) {
                Storage::makeDirectory('backups');
            }

            // Generar backup usando mysqldump
            $command = sprintf(
                'mysqldump --user=%s --password=%s --host=%s %s > %s',
                config('database.connections.mysql.username'),
                config('database.connections.mysql.password'),
                config('database.connections.mysql.host'),
                config('database.connections.mysql.database'),
                storage_path('app/' . $filePath)
            );

            exec($command, $output, $returnCode);

            if ($returnCode !== 0) {
                throw new \Exception('Error al generar el backup de la base de datos');
            }

            $fileSize = Storage::size($filePath);

            // Guardar registro en la base de datos
            $backup = Backup::create([
                'filename' => $filename,
                'file_path' => $filePath,
                'type' => 'manual',
                'frequency' => null,
                'file_size' => $fileSize,
                'user_id' => auth()->id(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Copia de seguridad creada correctamente',
                'backup' => $backup
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear la copia de seguridad: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Descargar copia de seguridad
     */
    public function downloadBackup(Backup $backup)
    {
        if (!$backup->fileExists()) {
            abort(404, 'Archivo de backup no encontrado');
        }

        return Storage::download($backup->file_path, $backup->filename);
    }

    /**
     * Eliminar copia de seguridad
     */
    public function deleteBackup(Backup $backup)
    {
        try {
            $backup->deleteFile();
            $backup->delete();

            return response()->json([
                'success' => true,
                'message' => 'Copia de seguridad eliminada correctamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar la copia de seguridad: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Crear nuevo rol
     */
    public function createRole(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:roles,name',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $role = Role::create(['name' => $request->name]);
            
            if ($request->has('permissions')) {
                $permissions = Permission::whereIn('id', $request->permissions)->get();
                $role->syncPermissions($permissions);
            }

            return response()->json([
                'success' => true,
                'message' => 'Rol creado correctamente',
                'role' => $role->load('permissions')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el rol: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualizar rol
     */
    public function updateRole(Request $request, Role $role)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $role->update(['name' => $request->name]);
            
            if ($request->has('permissions')) {
                $permissions = Permission::whereIn('id', $request->permissions)->get();
                $role->syncPermissions($permissions);
            } else {
                $role->syncPermissions([]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Rol actualizado correctamente',
                'role' => $role->load('permissions')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el rol: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Eliminar rol
     */
    public function deleteRole(Role $role)
    {
        try {
            // No permitir eliminar roles del sistema
            if (in_array($role->name, ['Super', 'Admin'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se puede eliminar este rol del sistema'
                ], 422);
            }

            $role->delete();

            return response()->json([
                'success' => true,
                'message' => 'Rol eliminado correctamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el rol: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Asignar rol a usuario
     */
    public function assignRoleToUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'role_id' => 'required|exists:roles,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = User::findOrFail($request->user_id);
            $role = Role::findOrFail($request->role_id);
            
            $user->assignRole($role);

            return response()->json([
                'success' => true,
                'message' => 'Rol asignado correctamente al usuario'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al asignar el rol: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remover rol de usuario
     */
    public function removeRoleFromUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'role_id' => 'required|exists:roles,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = User::findOrFail($request->user_id);
            $role = Role::findOrFail($request->role_id);
            
            $user->removeRole($role);

            return response()->json([
                'success' => true,
                'message' => 'Rol removido correctamente del usuario'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al remover el rol: ' . $e->getMessage()
            ], 500);
        }
    }
}