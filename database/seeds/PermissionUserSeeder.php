<?php
use App\Models\Person\PermissionUser;
use Illuminate\Database\Seeder;

class PermissionUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(\Faker\Generator $faker)
    {
        factory(PermissionUser::class, 10)->make()->each(function ($permissionUser) use ($faker) {
            $users = APP\Models\Person\Role::all();
            $permissions = APP\Models\Person\Permission::all();
            $permissionUser->user_id = $faker->randomElement($users)->id;
            $permissionUser->permission_id = $faker->randomElement($permissions)->id;
            $permissionUser->save();
        });
    }
}
