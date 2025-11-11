<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class ReceptionistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Crear usuario recepcionista (Admin)
        $receptionist = User::create([
            'name' => 'María González',
            'email' => 'recepcion@hotel.com',
            'password' => Hash::make('recepcion123'),
            'role' => 'Admin',
            'is_active' => true,
            'random_key' => Str::random(60),
        ]);

        // Crear o obtener el rol Admin
        $adminRole = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);

        // Definir permisos específicos para recepcionista (Admin)
        $receptionistPermissions = [
            // Gestión de usuarios (solo visualización)
            'user.show',
            
            // Gestión de clientes
            'customer.index', 'customer.create', 'customer.store', 'customer.show', 'customer.edit', 'customer.update', 'customer.destroy',
            
            // Gestión de transacciones
            'transaction.index', 'transaction.create', 'transaction.store', 'transaction.show', 'transaction.edit', 'transaction.update', 'transaction.destroy',
            
            // Gestión de instalaciones
            'facility.index', 'facility.create', 'facility.store', 'facility.show', 'facility.edit', 'facility.update', 'facility.destroy',
            
            // Proceso de check-in
            'check-in.index', 'check-in.search', 'check-in.create', 'check-in.store', 'check-in.select-room', 'check-in.confirmation', 'check-in.process',
            
            // Reservas de habitaciones
            'transaction.reservation.createIdentity', 'transaction.reservation.pickFromCustomer', 'transaction.reservation.storeCustomer',
            'transaction.reservation.viewCountPerson', 'transaction.reservation.chooseRoom', 'transaction.reservation.confirmation', 'transaction.reservation.payDownPayment',
            
            // Gestión de pagos (solo para transacciones)
            'transaction.payment.create', 'transaction.payment.store',
            
            // Gestión de imágenes (solo para habitaciones)
            'image.store', 'image.destroy',
            
            // Actividades y notificaciones
            'activity-log.index', 'activity-log.all', 'notification.index', 'notification.markAllAsRead', 'notification.routeTo',
            
            // Dashboard
            'dashboard.index',
        ];

        // Crear permisos si no existen
        foreach ($receptionistPermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Asignar permisos al rol Admin
        $adminRole->syncPermissions($receptionistPermissions);

        // Asignar el rol al usuario recepcionista
        $receptionist->assignRole($adminRole);

        $this->command->info('Usuario recepcionista creado exitosamente:');
        $this->command->info('Nombre: María González');
        $this->command->info('Email: recepcion@hotel.com');
        $this->command->info('Contraseña: recepcion123');
        $this->command->info('Rol: Admin (recepcionista)');
        $this->command->info('Estado: Activo');
        $this->command->info('Permisos asignados: ' . count($receptionistPermissions) . ' permisos');
        $this->command->info('');
        $this->command->info('Permisos del recepcionista:');
        $this->command->info('- Gestión de clientes');
        $this->command->info('- Gestión de transacciones');
        $this->command->info('- Proceso de check-in');
        $this->command->info('- Reservas de habitaciones');
        $this->command->info('- Gestión de instalaciones');
        $this->command->info('- Pagos de transacciones');
        $this->command->info('- Dashboard y notificaciones');
        $this->command->info('');
        $this->command->info('NO tiene acceso a:');
        $this->command->info('- Gestión de habitaciones (room)');
        $this->command->info('- Gestión de tipos de habitación (type)');
        $this->command->info('- Gestión de estados de habitación (roomstatus)');
        $this->command->info('- Reportes financieros completos');
        $this->command->info('- Gráficos y estadísticas avanzadas');
    }
}
