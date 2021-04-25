<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BotsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('bots')->insert([
            [
                'id' => 1,
                'language_id' => 1,
                'user_id' => 1,
                'slug' => "dilly",
                'name' => "Dilly",
                'summary' => "The default conversation bot",
                'description' => "The default conversation bot - comes as default with a fresh install of The Lemur Engine",
                'default_response' => 'I don\'t have a response for that',
                'lemurtar_url' => 'https://lemurtar.com/?accessoriesType=Blank&avatarStyle=Circle&clotheColor=Pink&clotheType=Overall&eyeType=Default&eyebrowType=DefaultNatural&facialHairColor=Brown&facialHairType=Blank&graphicType=Pizza&hairColor=Black&hatColor=Blue02&mouthType=Twinkle&skinColor=Tanned&topType=ShortHairShaggyMullet',
                'status' => 'A',
                'image' => 'widgets/dilly.png',
                'is_public' => 1,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')

            ]

        ]);
    }
}
