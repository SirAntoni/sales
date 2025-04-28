<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Permission::create(['name' => 'dashboard','description' => 'Dashboard']);
        Permission::create(['name' => 'store','description' => 'Almacén']);
        Permission::create(['name' => 'purchases','description' => 'Compras']);
        Permission::create(['name' => 'sales','description' => 'Ventas']);
        Permission::create(['name' => 'reports','description' => 'Reportes']);
        Permission::create(['name' => 'users','description' => 'Usuarios']);
        Permission::create(['name' => 'settings','description' => 'Configuración']);
        Permission::create(['name' => 'kardex','description' => 'Kardex']);
        Permission::create(['name' => 'documents','description' => 'Documentos']);

        Permission::create(['name' => 'update','description' => 'Editar']);
        Permission::create(['name' => 'delete','description' => 'Eliminar']);
    }
}
