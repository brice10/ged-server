<?php
use App\Models\Person\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(\Faker\Generator $faker)
    {
        factory(Role::class, 50)->make()->each(function ($role) use ($faker) {
            $role->save();
        });
    }
}
