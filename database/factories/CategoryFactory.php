<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Category;
use Carbon\Carbon;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\DB;

$factory->define(Category::class, function (Faker $faker) {

    $word = strtoupper($faker->word);
    $wordTwo = strtoupper($faker->word);
    $wordThree = strtoupper($faker->word);

    $user_ids = DB::table('users')->pluck('id', 'id');
    $user_id = $faker->randomElement($user_ids);

    $category_group_ids = DB::table('category_groups')->select('id')->get();
    $category_group_id = $faker->randomElement($category_group_ids)->id;


    return [
        'user_id' => $user_id,
        'category_group_id' => $category_group_id,
        'slug' => uniqid($word.'-'.$wordTwo.'-'.$wordThree, false),
        'pattern' => $word,
        'regexp_pattern' => $word,
        'first_letter_pattern' => $word[0],
        'topic' => $wordTwo,
        'regexp_topic' => $wordTwo,
        'first_letter_topic' => $wordTwo[0],
        'that' => $wordThree,
        'regexp_that' => $wordThree,
        'first_letter_that' => $wordThree[0],
        'template' => $faker->text,
        'status' => $faker->randomElement(array_flip(config('lemur_dropdown.item_status'))),
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now()
    ];
});
