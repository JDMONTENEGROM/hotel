<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

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
            'name' => 'Carlos Rodríguez',
            'email' => 'recepcion@hotel.com',
            'password' => Hash::make('recepcion123'),
            'role' => 'Admin',
            'is_active' => true,
            'random_key' => \Illuminate\Support\Str::random(60),
        ]);

        $this->command->info('Usuario recepcionista creado exitosamente:');
        $this->command->info('Nombre: Carlos Rodríguez');
        $this->command->info('Email: recepcion@hotel.com');
        $this->command->info('Contraseña: recepcion123');
        $this->command->info('Rol: Admin');
        $this->command->info('Estado: Activo');
    }
}
