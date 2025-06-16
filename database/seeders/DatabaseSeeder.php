<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $this->call(RoleSeeder::class);
        User::create([
            'name' => 'Eduardo Lopez',
            'email' => 'administrador@wari.pe',
            'password' => bcrypt('12345678'),
        ])->givePermissionTo(Permission::all());

    }
}
