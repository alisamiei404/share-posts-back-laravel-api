<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Post;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $posts = [
        //     [
        //         'id' => 1,
        //         'user_id' => 1,
        //         'title' => Str::random(10),
        //         'slug' => Str::random(7),
        //         'content' => Str::random(25)
        //     ]
        // ];
            
        // foreach ($users as $key => $value) {
        //     User::create($value);
        // }

        for ($i=1; $i < 21 ; $i++) { 
            $randomUser = rand(3, 5);
            $randomTitle = rand(6, 15);
            $randomContent = rand(15, 30);
            $randomStatus = rand(0, 2);
            Post::create([
                    'id' => $i,
                    'user_id' => $randomUser,
                    'title' => Str::random($randomTitle),
                    'slug' => Str::random(7),
                    'content' => Str::random($randomContent),
                    'status' => $randomStatus
                ]);
        }
    }
}
