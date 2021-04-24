<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class WordSpellingGroupsTableSeeder extends Seeder
{


    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        //create 10 users
        //factory(User::class, 1)->create();


        DB::table('word_spelling_groups')->insert([
            [
                'id' => 1,
                'language_id' => 1,
                'user_id' => 1,
                'slug' => 'default-spelling-set',
                'name' => 'Default spelling set',
                'description' => 'Spelling corrections for common mistakes',
                'is_master' => 1,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')

            ]

        ]);


    }
}
