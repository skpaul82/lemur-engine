<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\SetValue;
use Carbon\Carbon;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\DB;

$factory->define(SetValue::class, function (Faker $faker) {


    $set_ids = DB::table('sets')->pluck('id', 'id');
    $set_id = $faker->randomElement($set_ids);

    $user_ids = DB::table('users')->pluck('id', 'id');
    $user_id = $faker->randomElement($user_ids);

    $word = uniqid($faker->word.$faker->word, false);

    return [
        'set_id' => $set_id,
        'user_id' => $user_id,
        'slug' => $word,
        'value' =>$word,
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now()
    ];
});
