<?php
use App\Models\Person\PermissionRole;
use Illuminate\Database\Seeder;

class PermissionRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(\Faker\Generator $faker)
    {
        factory(PermissionRole::class, 10)->make()->each(function ($permissionRole) use ($faker) {
            $roles = APP\Models\Person\Role::all();
            $permissions = APP\Models\Person\Permission::all();
            $permissionRole->role_id = $faker->randomElement($roles)->id;
            $permissionRole->permission_id = $faker->randomElement($permissions)->id;
            $permissionRole->save();
        });
    }
}
