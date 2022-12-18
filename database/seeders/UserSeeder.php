<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'id' => 1,
                'name' => 'مهمان',
                'email' => 'mehman@gmail.com',
                'slug' => Str::random(5),
                'password' => bcrypt('mehman1234'),
                'type' => 'admin',
                'status' => 'deactive'
            ],
            [
                'id' => 2,
                'name' => 'ادمین',
                'email' => 'admin@gmail.com',
                'slug' => Str::random(5),
                'password' => bcrypt('admin1234'),
                'type' => 'admin'
            ],
            [
                'id' => 3,
                'name' => 'علی',
                'email' => 'ali@gmail.com',
                'slug' => Str::random(5),
                'password' => bcrypt('ali1234'),
                'type' => 'user'
            ],
            [
                'id' => 4,
                'name' => 'الی',
                'email' => 'ely@gmail.com',
                'slug' => Str::random(5),
                'password' => bcrypt('ely1234'),
                'type' => 'user'
            ],
            [
                'id' => 5,
                'name' => 'ebi',
                'email' => 'ebi@gmail.com',
                'slug' => Str::random(5),
                'password' => bcrypt('ebi1234'),
                'status' => 'deactive'
            ]
        ];
            
        foreach ($users as $key => $value) {
            User::create($value);
        }
    }
}
