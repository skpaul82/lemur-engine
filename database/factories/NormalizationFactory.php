<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Normalization;
use Carbon\Carbon;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\DB;

$factory->define(Normalization::class, function (Faker $faker) {

    $word = uniqid($faker->word.$faker->word, false);

    $language_ids = DB::table('languages')->pluck('id', 'id');
    $language_id = $faker->randomElement($language_ids);

    return [
        'language_id' => $language_id,
        'slug' => $word,
        'original_value' => $word,
        'normalized_value' => $word,
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now()
    ];
});
