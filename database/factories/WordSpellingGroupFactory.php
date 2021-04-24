<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\WordSpellingGroup;
use Carbon\Carbon;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\DB;

$factory->define(WordSpellingGroup::class, function (Faker $faker) {


    $word = uniqid($faker->word.$faker->word, false);

    $language_ids = DB::table('languages')->pluck('id', 'id');
    $language_id = $faker->randomElement($language_ids);

    $user_ids = DB::table('users')->pluck('id', 'id');
    $user_id = $faker->randomElement($user_ids);

    return [
        'language_id' => $language_id,
        'user_id' => $user_id,
        'slug' => $word,
        'name' => $word,
        'description' => $word,
        'is_master' => $faker->boolean,
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now()
    ];
});
