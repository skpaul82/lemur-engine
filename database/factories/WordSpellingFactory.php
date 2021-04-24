<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\WordSpelling;
use Carbon\Carbon;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\DB;

$factory->define(WordSpelling::class, function (Faker $faker) {

    $word = uniqid($faker->word.$faker->word, false);

    $user_ids = DB::table('users')->pluck('id', 'id');
    $user_id = $faker->randomElement($user_ids);

    $word_spelling_group_ids = DB::table('word_spelling_groups')->pluck('id', 'id');
    $word_spelling_group_id = $faker->randomElement($word_spelling_group_ids);

    return [
        'user_id' => $user_id,
        'word_spelling_group_id' => $word_spelling_group_id,
        'slug' => $word,
        'word' => $word,
        'replacement' => $faker->word,
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now()
    ];
});
