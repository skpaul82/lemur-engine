<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Language;
use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(Language::class, function (Faker $faker) {



    $word = substr(uniqid(mt_rand(9999, 9999999), false), 0, 10);

    return [
        'slug' => $word,
        'name' => $word,
        'description' => $faker->word,
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now()
    ];
});
