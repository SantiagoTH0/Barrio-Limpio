<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\IncidentType;
use App\Models\Crew;
use App\Models\Report;
use App\Models\User;
use Illuminate\Support\Carbon;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // Tipos de incidencias
        $types = collect([
            ['name' => 'Basura acumulada', 'description' => 'Residuos sólidos en vía pública'],
            ['name' => 'Lotes baldíos', 'description' => 'Terrenos sin mantenimiento'],
            ['name' => 'Alcantarillado', 'description' => 'Problemas con desagües'],
            ['name' => 'Iluminación', 'description' => 'Alumbrado público defectuoso'],
            ['name' => 'Escombros', 'description' => 'Restos de construcción'],
        ])->map(fn($t) => IncidentType::firstOrCreate(['name' => $t['name']], ['description' => $t['description']]));

        // Cuadrillas
        $crews = collect([
            ['name' => 'Cuadrilla Norte', 'zone' => 'Norte', 'status' => 'active'],
            ['name' => 'Cuadrilla Sur', 'zone' => 'Sur', 'status' => 'active'],
            ['name' => 'Cuadrilla Centro', 'zone' => 'Centro', 'status' => 'active'],
        ])->map(fn($c) => Crew::firstOrCreate(['name' => $c['name']], ['zone' => $c['zone'], 'status' => $c['status']]));

        // Usuarios base
        $citizen = User::where('role', User::ROLE_CITIZEN)->first();
        $official = User::where('role', User::ROLE_OFFICIAL)->first();

        // Usuarios con rol 'crew' (equipo)
        $crewUsers = collect([
            ['name' => 'María Equipo', 'email' => 'maria.equipo@example.com'],
            ['name' => 'José Equipo', 'email' => 'jose.equipo@example.com'],
            ['name' => 'Laura Equipo', 'email' => 'laura.equipo@example.com'],
        ])->map(function ($u) {
            return User::firstOrCreate(
                ['email' => $u['email']],
                [
                    'name' => $u['name'],
                    'password' => bcrypt('password'),
                    'role' => User::ROLE_CREW,
                ]
            );
        });

        // Crear ~40 reportes demo
        $zones = ['Norte','Sur','Centro','Este','Oeste'];
        $statuses = [Report::STATUS_PENDING, Report::STATUS_IN_PROGRESS, Report::STATUS_RESOLVED];

        $now = Carbon::now();
        for ($i = 0; $i < 40; $i++) {
            $type = $types->random();
            $zone = $zones[array_rand($zones)];
            $status = $statuses[array_rand($statuses)];
            $created = $now->copy()->subDays(rand(0, 60))->subHours(rand(0, 23));

            $report = Report::create([
                'type_id' => $type->id,
                'user_id' => $citizen?->id ?? $official?->id,
                'crew_id' => rand(0,1) ? $crews->random()->id : null,
                'zone' => $zone,
                'status' => $status,
                'location_text' => 'Ubicación aproximada',
                'photo_url' => null,
                'description' => 'Descripción de reporte demo',
                'created_at' => $created,
                'updated_at' => $created,
            ]);

            // Asignar a un usuario de equipo aleatorio si el estado no es pendiente (para mostrar en su dashboard)
            if ($status !== Report::STATUS_PENDING && $crewUsers->isNotEmpty() && rand(0,1)) {
                $report->assigned_user_id = $crewUsers->random()->id;
            }

            if ($status !== Report::STATUS_PENDING) {
                $started = $created->copy()->addHours(rand(1, 24));
                $report->started_at = $started;
                if ($status === Report::STATUS_RESOLVED) {
                    $report->resolved_at = $started->copy()->addHours(rand(1, 48));
                }
            }

            $report->save();
        }
    }
}