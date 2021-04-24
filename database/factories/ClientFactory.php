<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Client;
use Carbon\Carbon;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\DB;

$factory->define(Client::class, function (Faker $faker) {

    $bot_ids = DB::table('bots')->pluck('id', 'id');
    $bot_id = $faker->randomElement($bot_ids);

    return [
        'bot_id' => $bot_id,
        'slug' => uniqid($faker->word, false),
        'is_banned' => $faker->boolean,
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now()
    ];
});
