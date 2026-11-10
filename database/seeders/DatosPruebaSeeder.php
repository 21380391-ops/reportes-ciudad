<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Zone;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatosPruebaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'nombre' => 'Administrador General',
            'email' => 'admin@test.com',
            'password' => Hash::make('123'),
            'rol' => 'administrador',
            'telefono' => '8340000001',
        ]);

        User::create([
            'nombre' => 'Moderador Obras',
            'email' => 'moderador@test.com',
            'password' => Hash::make('123'),
            'rol' => 'moderador',
            'telefono' => '8340000002',
        ]);

        User::create([
            'nombre' => 'Ciudadano Prueba',
            'email' => 'usuario@test.com',
            'password' => Hash::make('123'),
            'rol' => 'usuario',
            'telefono' => '8340000003',
        ]);

        $categorias = [
            'Bache',
            'Fuga de agua',
            'Alumbrado público',
            'Socavón',
            'Acumulación de basura'
        ];

        foreach ($categorias as $cat) {
            Category::create(['nombre' => $cat]);
        }

        $zonas = [
            'Zona Centro',
            'Sector Norte',
            'Sector Sur',
            'Sector Oriente',
            'Sector Poniente'
        ];

        foreach ($zonas as $zona) {
            Zone::create(['nombre' => $zona]);
        }
    }
}
