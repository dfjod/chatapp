<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Chat;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'username' => 'Bob',
            'email' => 'bob@bob.bob',
        ]);

        User::factory()->create([
            'username' => 'Tom',
            'email' => 'tom@tom.tom',
        ]);

        User::factory()->create([
            'username' => 'Von',
            'email' => 'von@von.von',
        ]);
    }
}
