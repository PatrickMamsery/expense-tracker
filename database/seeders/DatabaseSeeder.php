<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        // $user = User::factory()->create([
        //     'name' => 'Patrick Mamsery',
        //     'email' => 'pkrobert1612@gmail.com',
        // ]);

        $categories = Category::factory(6)->create([
            'user_id' => 1,
        ]);
    }
}
