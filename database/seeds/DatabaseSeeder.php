<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RolesTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(LanguagesTableSeeder::class);
        $this->call(BotsTableSeeder::class);
        $this->call(BotPropertiesTableSeeder::class);
        $this->call(WordSpellingGroupsTableSeeder::class);
        $this->call(WordSpellingsTableSeeder::class);
        $this->call(WordTransformationsTableSeeder::class);
        $this->call(CategoryGroupsTableSeeder::class);
        $this->call(CategoriesTableSeeder::class);
        $this->call(BotCategoryGroupsTableSeeder::class);


    }
}
