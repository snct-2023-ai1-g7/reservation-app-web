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
            'status' => 'not using',
            'user_id' => 'room_01',
            'tag_id' => 'abc01'
        ]);
    }
}
