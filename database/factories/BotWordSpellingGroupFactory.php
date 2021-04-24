<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\BotWordSpellingGroup;
use Carbon\Carbon;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\DB;

$factory->define(BotWordSpellingGroup::class, function (Faker $faker) {

    $slug = uniqid($faker->word, false);

    $user_ids = DB::table('users')->pluck('id', 'id');
    $user_id = $faker->randomElement($user_ids);

    $bot_ids = DB::table('bots')->pluck('id', 'id');
    $bot_id = $faker->randomElement($bot_ids);

    $word_spelling_group_ids = DB::table('word_spelling_groups')->pluck('id', 'id');
    $word_spelling_group_id = $faker->randomElement($word_spelling_group_ids);


    return [
        'slug' => $slug,
        'user_id' => $user_id,
        'bot_id' => $bot_id,
        'word_spelling_group_id' => $word_spelling_group_id,
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now()
    ];
});
