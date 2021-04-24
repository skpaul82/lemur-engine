<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Turn;
use Carbon\Carbon;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\DB;

$factory->define(Turn::class, function (Faker $faker) {

    $conversation_ids = DB::table('conversations')->pluck('id', 'id');
    $conversation_id = $faker->randomElement($conversation_ids);

    $category_ids = DB::table('categories')->pluck('id', 'id');
    $category_id = $faker->randomElement($category_ids);

    return [
        'conversation_id' => $conversation_id,
        'category_id' => $category_id,
        'client_category_id' => null,
        'slug' => uniqid($faker->word, false),
        'input' => $faker->word,
        'output' => $faker->text,
        'source' => $faker->randomElement(array_flip(config('lemur_dropdown.turn_source'))),
        'is_display' => $faker->boolean(),
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now()
    ];
});
