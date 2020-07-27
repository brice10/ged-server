<?php
use App\Models\Person\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(\Faker\Generator $faker)
    {
        factory(Permission::class, 50)->make()->each(function ($permission) use ($faker) {
            $permission->save();
        });
    }
}
