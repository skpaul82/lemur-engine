<?php

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{


    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('users')->insert([
            [
                'id' => 1,
                'slug' => 'admin-adam',
                'name' => 'Admin Adam',
                'email' => 'admin@lemurengine.local',
                'password' => Hash::make('password'), // password
                'email_verified_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')

            ]
        ]);

        User::find(1)->assignRole('admin');

        //uncomment if you want to create a test non admin person as well
        /*DB::table('users')->insert([
            [
                'id' => 2,
                'slug' => 'author-adam',
                'name' => 'Author Adam',
                'email' => 'author@lemurengine.local',
                'password' => Hash::make('password'), // password
                'email_verified_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')

            ]
        ]);

        User::find(2)->assignRole('author');
        */

    }
}
