<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\MapValue;
use Carbon\Carbon;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\DB;

$factory->define(MapValue::class, function (Faker $faker) {


    $word = uniqid($faker->word.$faker->word, false);

    $user_ids = DB::table('users')->pluck('id', 'id');
    $user_id = $faker->randomElement($user_ids);

    $map_ids = DB::table('maps')->pluck('id', 'id');
    $map_id = $faker->randomElement($map_ids);

    return [
        'map_id' => $map_id,
        'user_id' => $user_id,
        'slug' => $word,
        'word' => $word,
        'value' =>$word,
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now()
    ];
});
