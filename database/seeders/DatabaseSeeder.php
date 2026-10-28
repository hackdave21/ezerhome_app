<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database. Tous les seeders sont rejouables sans doublon.
     */
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
            PageSeeder::class,
            CharterSeeder::class,
        ]);
    }
}
