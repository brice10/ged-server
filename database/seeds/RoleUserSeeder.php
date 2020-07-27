<?php
use App\Models\Person\RoleUser;
use Illuminate\Database\Seeder;

class RoleUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(\Faker\Generator $faker)
    {
        factory(RoleUser::class, 10)->make()->each(function ($roleUser) use ($faker) {
            $users = APP\Models\Person\User::all();
            $roles = APP\Models\Person\Role::all();
            $roleUser->user_id = $faker->randomElement($users)->id;
            $roleUser->role_id = $faker->randomElement( $roles)->id;
            $roleUser->save();
        });
    }
}
