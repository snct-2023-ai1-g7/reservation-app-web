<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'user_id' => 'room_01',
            'password' => 'password',
            'user_type' => 'room',
            'room_number' => 1
        ]);
        User::create([
            'user_id' => 'room_02',
            'password' => 'password',
            'user_type' => 'room',
            'room_number' => 2 
        ]);
        User::create([
            'user_id' => 'admin',
            'password' => 'password',
            'user_type' => 'admin',
            'room_number' => null
        ]);
        User::create([
            'user_id' => 'demo',
            'password' => 'password',
            'user_type' => 'guest',
            'room_number' => null
        ]);
    }
}
