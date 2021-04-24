<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\EmptyResponse;
use Carbon\Carbon;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\DB;

$factory->define(EmptyResponse::class, function (Faker $faker) {

    $bot_ids = DB::table('bots')->pluck('id', 'id');
    $bot_id = $faker->randomElement($bot_ids);

    return [
        'bot_id' => $bot_id,
        'slug' => uniqid($faker->word, false),
        'input' => $faker->text,
        'occurrences' => $faker->randomNumber(1),
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now()
    ];
});
