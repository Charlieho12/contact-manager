<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {

        // Create a default admin user for production
        \App\Models\User::updateOrCreate(
            [ 'email' => 'admin@admin.com' ],
            [
                'name' => 'Admin',
                'password' => bcrypt('admin123'),
            ]
        );
    }
}
