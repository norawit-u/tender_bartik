<?php

use Illuminate\Database\Seeder;

class supervisorSubordinatesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i < 10; $i++) {
            Super::create([
                'name' => $faker->name,
                'status' => $faker->name,
                'description' => $faker->paragraph,
                'assignee' => $faker->randomElement(User::pluck('id')->toArray()),
                'assigner' => $faker->randomElement(User::pluck('id')->toArray()),
            ]);
        }
    }
}
