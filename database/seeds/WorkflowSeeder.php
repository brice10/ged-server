<?php

use App\Models\Workflow\Workflow;
use Illuminate\Database\Seeder;

class WorkflowSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(\Faker\Generator $faker)
    {
        factory(Workflow::class, 10)->make()->each(function ($workflow) use ($faker) {
            $users = App\Models\Person\User::all();
            $workflow->user_id = $faker->randomElement($users)->id;
            $workflow->save();
        });
    }
}
