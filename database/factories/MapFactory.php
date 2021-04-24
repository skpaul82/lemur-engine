<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Map;
use Carbon\Carbon;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\DB;

$factory->define(Map::class, function (Faker $faker) {

    $word = uniqid($faker->word.$faker->word, false);

    $user_ids = DB::table('users')->pluck('id', 'id');
    $user_id = $faker->randomElement($user_ids);

    return [
        'user_id' => $user_id,
        'slug' => $word,
        'name' => $word,
        'description' => $word,
        'is_master' => $faker->boolean,
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now()
    ];
});
