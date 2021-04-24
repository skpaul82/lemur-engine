<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\WordTransformation;
use Carbon\Carbon;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\DB;

$factory->define(WordTransformation::class, function (Faker $faker) {

    $wordTwo = $faker->word;
    $wordThree = $faker->word;

    $word = uniqid($faker->word.$faker->word, false);

    $language_ids = DB::table('languages')->pluck('id', 'id');
    $language_id = $faker->randomElement($language_ids);

    $user_ids = DB::table('users')->pluck('id', 'id');
    $user_id = $faker->randomElement($user_ids);

    return [
        'user_id' => $user_id,
        'language_id' => $language_id,
        'slug' => $word,
        'first_person_form' => $word,
        'second_person_form' => $wordTwo,
        'third_person_form' => $wordThree,
        'third_person_form_female' => $wordThree,
        'third_person_form_male' => $wordThree,
        'is_master' => $faker->boolean,
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now()
    ];
});
