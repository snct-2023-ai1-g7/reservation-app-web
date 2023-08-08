<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Tag::create([
            'status' => 'left',
            'user_id' => 'room_01',
            'uid' => '4519417819317144'
        ]);
    }
}
