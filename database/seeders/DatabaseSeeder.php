<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleAndPermissionSeeder::class,
            LembagaSeeder::class,
        ]);

        // Create initial Super Admin if not exists
        if (!User::where('email', 'admin@dinas.go.id')->exists()) {
            $user = User::create([
                'name' => 'Super Admin Dinas',
                'email' => 'admin@dinas.go.id',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
            ]);
            $user->assignRole('super_admin');
        }
    }
}
