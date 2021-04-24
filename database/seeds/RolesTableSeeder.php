<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolesTableSeeder extends Seeder
{

    /**
     *
     * this app has 6 roles....
     * system
     * admin - read any author
     * super bot author
     * org admin
     * org bot author
     * bot author
     */
    private $superAdminPermissions = ['read any data', 'write any data','read own data', 'write own data'];
    private $authorPermissions = ['read own data', 'write own data'];


    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        //create all the permissions
        $this->createAllPermissions();

        //create all the roles and grant the permissions
        $this->createsRoleAndGrantPermissions('admin', $this->superAdminPermissions); //this user is an admin and a user
        $this->createsRoleAndGrantPermissions('admin', $this->authorPermissions); //this user is an admin and a author

        $this->createsRoleAndGrantPermissions('author', $this->authorPermissions); //this user just a author


    }

    /**
     * Loop through all the permissions and create them in memory
     */
    public function createAllPermissions(){

        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        foreach($this->superAdminPermissions as $permission){
            Permission::firstOrCreate(['guard_name' => 'web', 'name' => $permission]);
            Permission::firstOrCreate(['guard_name' => 'api', 'name' => $permission]);
        }

        foreach($this->authorPermissions as $permission){
            Permission::firstOrCreate(['guard_name' => 'web', 'name' => $permission]);
            Permission::firstOrCreate(['guard_name' => 'api', 'name' => $permission]);
        }


    }


    /**
     * generate the org admin role then grant the permissions
     * @param $name
     * @param $rolePermissions
     */
    public function createsRoleAndGrantPermissions($name, $rolePermissions){

        // this can be done as separate statements
        $roleWeb = Role::firstOrCreate(['guard_name' => 'web', 'name' => $name]);
        $roleApi = Role::firstOrCreate(['guard_name' => 'api', 'name' => $name]);

        if(is_string($rolePermissions)){

            $roleWeb->givePermissionTo($rolePermissions);
            $roleApi->givePermissionTo($rolePermissions);


        }elseif(!empty($rolePermissions)){

            foreach($rolePermissions as $permission){
                $roleWeb->givePermissionTo($permission);
                $roleApi->givePermissionTo($permission);
            }

        }


    }


}
