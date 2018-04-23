<?php

use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
//        factory(App\User::class)->create(['email' => 'johndoe@scotch.io']);
        factory(App\User::class, 3)->create([
            'fname' => $faker->name,
            'lname' => $faker->name,
            'address' => $faker->paragraph,
            'telno' => $faker->name,
            'fb' => $faker->name,
            'ig' => $faker->name,
            'line' => $faker->name,
            'role' => 'Administrator',
            'department' => $faker->name,
            'image_path' => $faker->name,
        ]);
        factory(App\User::class, 3)->create([
            'fname' => $faker->name,
            'lname' => $faker->name,
            'address' => $faker->paragraph,
            'telno' => $faker->name,
            'fb' => $faker->name,
            'ig' => $faker->name,
            'line' => $faker->name,
            'role' => 'Supervisor',
            'department' => $faker->name,
            'image_path' => $faker->name,
        ]);
        factory(App\User::class, 3)->create([
            'fname' => $faker->name,
            'lname' => $faker->name,
            'address' => $faker->paragraph,
            'telno' => $faker->name,
            'fb' => $faker->name,
            'ig' => $faker->name,
            'line' => $faker->name,
            'role' => 'Subordinate',
            'department' => $faker->name,
            'supervisor_id' => rand(3, 6),
            'image_path' => $faker->name,
        ]);

    }
}
