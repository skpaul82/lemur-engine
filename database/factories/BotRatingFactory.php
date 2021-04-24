<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\BotRating;
use Carbon\Carbon;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\DB;

$factory->define(BotRating::class, function (Faker $faker) {

    $slug = uniqid($faker->word, false);

    $conversation_ids = DB::table('conversations')->pluck('id', 'id');
    $conversation_id = $faker->randomElement($conversation_ids);

    $bot_ids = DB::table('bots')->pluck('id', 'id');
    $bot_id = $faker->randomElement($bot_ids);

    return [
        'slug' => $slug,
        'conversation_id' => $conversation_id,
        'bot_id' => $bot_id,
        'rating' => $faker->randomDigitNotNull,
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now()
    ];
});
