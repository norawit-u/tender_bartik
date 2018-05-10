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

        $url = 'storage/' . (string)$request->images->store('images','public');
        $request->user()->image_path = $url;
        $request->user()->save();

//        factory(App\User::class)->create(['email' => 'johndoe@scotch.io']);
        factory(App\User::class, 3)->create([
            'fname' => "Adam",
            'lname' => "Warlock",
            'address' => $faker->paragraph,
            'telno' => $faker->e164PhoneNumber,
            'fb' => "Adam Warlock",
            'ig' => "Adam Warlock",
            'line' => "AdamWarlock",
            'role' => 'Administrator',
            'department' => "Computer",
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
