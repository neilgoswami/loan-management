<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Generate users for lenders and borrowers
        User::factory()->create([
            'email' => 'engineer@nedhelps.com'
        ]);
        User::factory()->count(10)->create();
    }
}
