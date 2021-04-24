<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\CategoryGroup;
use Carbon\Carbon;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\DB;

$factory->define(CategoryGroup::class, function (Faker $faker) {

    $word = uniqid($faker->word, false);

    $user_ids = DB::table('users')->pluck('id', 'id');
    $user_id = $faker->randomElement($user_ids);

    $language_ids = DB::table('languages')->pluck('id', 'id');
    $language_id = $faker->randomElement($language_ids);

    return [
        'user_id' => $user_id,
        'language_id' => $language_id,
        'slug' => $word,
        'name' => $word,
        'description' => $faker->text,
        'status' => $faker->randomElement(array_flip(config('lemur_dropdown.item_status'))),
        'is_master' => $faker->boolean,
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now()
    ];
});
