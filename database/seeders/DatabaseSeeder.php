<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin - Funcionario municipal
        User::updateOrCreate(
            ['email' => 'admin@barrio-limpio.local'],
            [
                'name' => 'Funcionario Municipal',
                'password' => Hash::make('admin123'),
                'role' => User::ROLE_OFFICIAL,
            ]
        );

        // Ciudadano
        User::updateOrCreate(
            ['email' => 'ciudadano@barrio-limpio.local'],
            [
                'name' => 'Ciudadano Demo',
                'password' => Hash::make('ciudadano123'),
                'role' => User::ROLE_CITIZEN,
            ]
        );

        // Equipo de limpieza / mantenimiento
        User::updateOrCreate(
            ['email' => 'equipo@barrio-limpio.local'],
            [
                'name' => 'Equipo de Limpieza',
                'password' => Hash::make('equipo123'),
                'role' => User::ROLE_CREW,
            ]
        );

        // Datos demo para panel del funcionario
        $this->call(\Database\Seeders\DemoDataSeeder::class);
    }
}
