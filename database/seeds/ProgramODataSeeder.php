<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProgramODataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::unprepared(file_get_contents('database/seeds/sql/category_groups_po.sql'));
        DB::unprepared(file_get_contents('database/seeds/sql/categories_a_po_bk.sql'));
        DB::unprepared(file_get_contents('database/seeds/sql/categories_b_po_bk.sql'));
        DB::unprepared(file_get_contents('database/seeds/sql/categories_c_po_bk.sql'));
        DB::unprepared(file_get_contents('database/seeds/sql/categories_d_po_bk.sql'));
        DB::unprepared(file_get_contents('database/seeds/sql/categories_e_po_bk.sql'));
    }
}
