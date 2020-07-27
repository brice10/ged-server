<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        Schema::disableForeignKeyConstraints();

        // $this->call(CityAndCountrySeeder::class);
        
        // module person
        $this->call([
            UserSeeder::class,
            RoleSeeder::class,
            PermissionSeeder::class,
            RoleUserSeeder::class,
            PermissionUserSeeder::class,
            PermissionRoleSeeder::class,
        ]);
        
        //module workflow
        $this->call([
            WorkflowSeeder::class
        ]);
    
        
      
        Schema::enableForeignKeyConstraints();
        Model::reguard();
    }
}
