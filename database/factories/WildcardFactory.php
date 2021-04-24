<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Wildcard;
use Carbon\Carbon;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\DB;

$factory->define(Wildcard::class, function (Faker $faker) {

    $conversation_ids = DB::table('conversations')->pluck('id', 'id');
    $conversation_id = $faker->randomElement($conversation_ids);

    return [
        'conversation_id' => $conversation_id,
        'slug' =>  uniqid($faker->word, false),
        'type' => $faker->word,
        'value' => $faker->word,
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now()
    ];
});
