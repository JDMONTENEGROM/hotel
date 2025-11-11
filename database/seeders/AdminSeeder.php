<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Crear o obtener usuario administrador con todos los permisos
        $admin = User::firstOrCreate(
            ['email' => 'admin@hotel.com'],
            [
                'name' => 'Administrador Principal',
                'password' => Hash::make('admin123'),
                'role' => 'Super',
                'is_active' => true,
                'random_key' => Str::random(60),
            ]
        );

        // Crear o obtener el rol Super
        $superRole = Role::firstOrCreate(['name' => 'Super', 'guard_name' => 'web']);

        // Definir todos los permisos del sistema
        $permissions = [
            // Gestión de usuarios
            'user.index', 'user.create', 'user.store', 'user.show', 'user.edit', 'user.update', 'user.destroy',
            
            // Gestión de clientes
            'customer.index', 'customer.create', 'customer.store', 'customer.show', 'customer.edit', 'customer.update', 'customer.destroy',
            
            // Gestión de transacciones
            'transaction.index', 'transaction.create', 'transaction.store', 'transaction.show', 'transaction.edit', 'transaction.update', 'transaction.destroy',
            
            // Gestión de habitaciones
            'room.index', 'room.create', 'room.store', 'room.show', 'room.edit', 'room.update', 'room.destroy',
            
            // Gestión de tipos de habitación
            'type.index', 'type.create', 'type.store', 'type.show', 'type.edit', 'type.update', 'type.destroy',
            
            // Gestión de estados de habitación
            'roomstatus.index', 'roomstatus.create', 'roomstatus.store', 'roomstatus.show', 'roomstatus.edit', 'roomstatus.update', 'roomstatus.destroy',
            
            // Gestión de instalaciones
            'facility.index', 'facility.create', 'facility.store', 'facility.show', 'facility.edit', 'facility.update', 'facility.destroy',
            
            // Proceso de check-in
            'check-in.index', 'check-in.search', 'check-in.create', 'check-in.store', 'check-in.select-room', 'check-in.confirmation', 'check-in.process',
            
            // Reservas de habitaciones
            'transaction.reservation.createIdentity', 'transaction.reservation.pickFromCustomer', 'transaction.reservation.storeCustomer',
            'transaction.reservation.viewCountPerson', 'transaction.reservation.chooseRoom', 'transaction.reservation.confirmation', 'transaction.reservation.payDownPayment',
            
            // Gestión de pagos
            'transaction.payment.create', 'transaction.payment.store', 'payment.index', 'payment.invoice',
            
            // Gestión de imágenes
            'image.store', 'image.destroy',
            
            // Reportes y gráficos
            'chart.dailyGuest', 'chart.dailyGuestPerMonth',
            
            // Reportes de ocupación e ingresos
            'reports.modal', 'reports.generate', 'reports.export',
            
            // Actividades y notificaciones
            'activity-log.index', 'activity-log.all', 'notification.index', 'notification.markAllAsRead', 'notification.routeTo',
            
            // Dashboard
            'dashboard.index',
        ];

        // Crear permisos si no existen
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Asignar todos los permisos al rol Super
        $superRole->syncPermissions($permissions);

        // Asignar el rol al usuario administrador
        $admin->assignRole($superRole);

        $this->command->info('Usuario administrador creado exitosamente:');
        $this->command->info('Nombre: Administrador Principal');
        $this->command->info('Email: admin@hotel.com');
        $this->command->info('Contraseña: admin123');
        $this->command->info('Rol: Super (con todos los permisos)');
        $this->command->info('Estado: Activo');
        $this->command->info('Permisos asignados: ' . count($permissions) . ' permisos');
    }
}
