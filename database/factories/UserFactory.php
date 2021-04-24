<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\User;
use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(User::class, function (Faker $faker) {

    $fakeId =  $faker->word.'-'.$faker->randomNumber().'-'.$faker->word.'-'.$faker->randomNumber();

    return [
        'slug' => $fakeId,
        'name' => $faker->firstName." ".$faker->lastName,
        'email' => $faker->randomNumber().$faker->email,
        'email_verified_at' => date('Y-m-d H:i:s'),
        'password' => $faker->password(10, 20),
        'api_token' => $faker->uuid,
        'remember_token' => $faker->uuid,
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now()
    ];
});
