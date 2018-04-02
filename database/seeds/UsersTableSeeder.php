<?php

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
        factory(App\User::class, 10)->create([
            'name' => $faker->name,
            'fname' => $faker->name,
            'lname' => $faker->name,
            'address' => $faker->paragraph,
            'telno' => $faker->name,
            'fb' => $faker->name,
            'ig' => $faker->name,
            'line' => $faker->name,
            'role' => $faker->name,
            'department' => $faker->name
      ]);
    }
}
