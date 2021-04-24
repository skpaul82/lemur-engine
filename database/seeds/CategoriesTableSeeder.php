<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::unprepared(file_get_contents('database/seeds/sql/categories_a.sql'));
        DB::unprepared(file_get_contents('database/seeds/sql/categories_b.sql'));
        DB::unprepared(file_get_contents('database/seeds/sql/categories_c.sql'));
    }
}
