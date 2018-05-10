<?php

use App\Task;
use App\User;
use Illuminate\Database\Seeder;

class TasksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Let's truncate our existing records to start from scratch.
//        Task::truncate();

        $faker = \Faker\Factory::create();

        // And now, let's create a few articles in our database:
        for ($i = 0; $i < 20; $i++) {
            Task::create([
                'name' => $faker->name,
                'status' => $faker->randomElement($array = array ('to-do','doing','done')),
                'description' => $faker->paragraph,
                'start' => $faker->dateTime($min = 'now', $timezone = null) ,
                'end' => $faker->dateTime($min = 'now', $timezone = null) ,
                'assignee' => $faker->randomElement(User::pluck('id')->toArray()),
                'assigner' => $faker->randomElement(User::pluck('id')->toArray()),
            ]);
        }
    }
}
