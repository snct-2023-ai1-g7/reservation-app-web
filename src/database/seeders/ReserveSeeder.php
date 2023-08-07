<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Reserve;

class ReserveSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Reserve::create([
            'start' => '06:00',
            'end' => '07:00',
            'room_number' => null
        ]);
        Reserve::create([
            'start' => "07:00",
            "end" => "08:00",
            "room_number" => null
        ]);
        Reserve::create([
            'start' => "08:00",
            "end" => "09:00",
            "room_number" => null
        ]);
        Reserve::create([
            'start' => "09:00",
            "end" => "10:00",
            "room_number" => null
        ]);
        Reserve::create([
            'start' => "10:00",
            "end" => "11:00",
            "room_number" => null
        ]);
        Reserve::create([
            'start' => "12:00",
            "end" => "13:00",
            "room_number" => null
        ]);
        Reserve::create([
            'start' => "13:00",
            "end" => "14:00",
            "room_number" => null
        ]);
        Reserve::create([
            'start' => "14:00",
            "end" => "15:00",
            "room_number" => null
        ]);
        Reserve::create([
            'start' => "15:00",
            "end" => "16:00",
            "room_number" => null
        ]);
        Reserve::create([
            'start' => "16:00",
            "end" => "17:00",
            "room_number" => null
        ]);
        Reserve::create([
            'start' => "17:00",
            "end" => "18:00",
            "room_number" => null
        ]);
        Reserve::create([
            'start' => "18:00",
            "end" => "19:00",
            "room_number" => null
        ]);
        Reserve::create([
            'start' => "19:00",
            "end" => "20:00",
            "room_number" => null
        ]);
        Reserve::create([
            'start' => "20:00",
            "end" => "21:00",
            "room_number" => null
        ]);
        Reserve::create([
            'start' => "21:00",
            "end" => "22:00",
            "room_number" => null
        ]);
        Reserve::create([
            'start' => "22:00",
            "end" => "23:00",
            "room_number" => null
        ]);
        Reserve::create([
            'start' => "23:00",
            "end" => "24:00",
            "room_number" => null
        ]);
    }
}
