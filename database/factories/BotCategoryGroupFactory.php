<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\BotCategoryGroup;
use App\Models\CategoryGroup;
use Carbon\Carbon;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\DB;

$factory->define(BotCategoryGroup::class, function (Faker $faker) {

    $slug = uniqid($faker->word, false);
    $user_ids = DB::table('users')->pluck('id', 'id');
    $user_id = $faker->randomElement($user_ids);

    $bot_ids = DB::table('bots')->pluck('id', 'id');
    $bot_id = $faker->randomElement($bot_ids);

    //after constantly getting unique key issues..
    //it is just easier to create a new category group
    $category_group_id = factory(CategoryGroup::class)->create(['language_id'=>1,'user_id'=>$user_id])->id;

    return [
        'slug' => $slug,
        'user_id' => $user_id,
        'bot_id' => $bot_id,
        'category_group_id' => $category_group_id,
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now()
    ];
});
