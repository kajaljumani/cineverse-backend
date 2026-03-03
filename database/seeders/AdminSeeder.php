<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::updateOrCreate(
            ['email' => 'admin@galaxywebservices.in'],
            [
                'name' => 'Galaxy Admin',
                'password' => \Illuminate\Support\Facades\Hash::make('Sanskaar@0811'),
                'is_admin' => true,
            ]
        );
    }
}
