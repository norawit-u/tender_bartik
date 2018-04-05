<?php

use App\Leave;
use App\Task;
use App\User;
use Illuminate\Database\Seeder;

class LeavesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Let's truncate our existing records to start from scratch.
        Leave::truncate();

        $faker = \Faker\Factory::create();

        // And now, let's create a few articles in our database:
        for ($i = 0; $i < 10; $i++) {
            Leave::create([
                'start' => $faker->dateTime($max = 'now', $timezone = null) ,
                'end' => $faker->dateTime($max = 'now', $timezone = null) ,
                'type' => $faker->tld,
                'status' => $faker->tld,
                'note' => $faker->tld,
                'leaver_id' => $faker->randomElement(User::pluck('id')->toArray()),
                'substitution_id' => $faker->randomElement(User::pluck('id')->toArray()),
                'task_id' => $faker->randomElement(Task::pluck('id')->toArray()),
            ]);
        }
    }
}
