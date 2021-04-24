<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Conversation;
use Carbon\Carbon;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\DB;

$factory->define(Conversation::class, function (Faker $faker) {

    $client_ids = DB::table('clients')->pluck('id', 'id');
    $client_id = $faker->randomElement($client_ids);

    $bot_ids = DB::table('bots')->pluck('id', 'id');
    $bot_id = $faker->randomElement($bot_ids);

    $randDate = Carbon::today()->subDays(rand(0, 365));


    return [
        'slug' => uniqid($faker->word, false),
        'bot_id' => $bot_id,
        'client_id' => $client_id,
        'allow_html' => $faker->boolean,
        'created_at' => $randDate,
        'updated_at' => $randDate
    ];
});
