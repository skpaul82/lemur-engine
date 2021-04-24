<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Bot;
use Carbon\Carbon;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\DB;

$factory->define(Bot::class, function (Faker $faker) {

    $name = uniqid($faker->word, false);
    $t1 = $faker->word;
    $t2 = $faker->word;
    $t3 = $faker->word;
    $t4 = $faker->word;
    $t5 = $faker->word;
    $t6 = $faker->word;
    $t7 = $faker->word;
    $t8 = $faker->word;
    $t9 = $faker->word;
    $t10 = $faker->word;
    $t11 = $faker->word;
    $t12 = $faker->word;
    $t13 = $faker->word;
    $t14 = $faker->word;


    $user_ids = DB::table('users')->pluck('id', 'id');
    $user_id = $faker->randomElement($user_ids);

    $language_ids = DB::table('languages')->pluck('id', 'id');
    $language_id = $faker->randomElement($language_ids);

    return [
        'user_id' => $user_id,
        'slug' => $name,
        'language_id' => $language_id,
        'name' => $name,
        'summary' => $faker->sentence(),
        'description' => $faker->paragraph(2),
        'lemurtar_url' => 'https://lemurtar.com/?accessoriesType='.$t1.'&avatarStyle='.$t2.'&clotheColor='.$t3.
            '&clotheType='.$t4.'&eyeType='.$t5.'&eyebrowType='.$t6.'&facialHairColor='.$t7.'&facialHairType='.$t8.
            '&graphicType='.$t9.'&hairColor='.$t10.'&hatColor='.$t11.'&mouthType='.$t12.'&skinColor='.$t13.
            '&topType='.$t14,
        'default_response' => 'pardon?',
        'is_public' => $faker->boolean,
        'is_master' => $faker->boolean,
        'status' => $faker->randomElement(array_flip(config('lemur_dropdown.item_status'))),
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now()
    ];
});
