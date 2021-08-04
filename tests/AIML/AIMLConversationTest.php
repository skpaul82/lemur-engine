<?php namespace Tests\AIML;

use App\Classes\LemurLog;
use App\Classes\TagStack;
use App\Models\User;
use BotCategoryGroupsTableSeeder;
use BotPropertiesTableSeeder;
use BotsTableSeeder;
use CategoriesTableSeeder;
use CategoryGroupsTableSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Log;
use LanguagesTableSeeder;
use RolesTableSeeder;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\Bot;
use UnitTestCategoriesTableSeeder;
use UsersTableSeeder;
use WordSpellingGroupsTableSeeder;
use WordSpellingsTableSeeder;
use WordTransformationsTableSeeder;

class AIMLConversationTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware;

    protected static $clientId = false;

    /**
     * In phpunit.xml the first 'test' that runs in this collection is the test to reset the db (setupDbTest.php)
     * Doing it this way saves time as there is no need to refresh the db on each test
     */
    public function setUp() :void
    {

        parent::setUp();

        $this->artisan('config:clear');
        $this->artisan('cache:clear');
        $this->artisan('route:clear');


        if (!self::$clientId) {
            // Do something once here for _all_ test subclasses.
            self::$clientId = uniqid('test', false);
        }
    }



    /**
     * @test
     * @dataProvider inputOutput
     */
    public function testSimpleConversation($name, $input, $expected)
    {

            $data = [
                'client'=>self::$clientId,
                'bot' => 'dilly',
                'html' => '1',
                'message' => $input];

            LemurLog::info("test data in ($input)", $data);

            $response = $this->post('/api/talk/bot', $data);
            $arr = json_decode($response->content(), true);
            $response->assertStatus(200);
            $this->assertTrue($arr['success']);


            LemurLog::info("test data out ($input)", $arr);

            if (is_array($expected)) {
                $this->assertContains(
                    $arr['data']['conversation']['output'],
                    $expected,
                    "Sent: '".$input. " in " .$name.' failed'
                );
            } elseif ($expected=='string') {
                $this->assertIsString(
                    $arr['data']['conversation']['output'],
                    "Sent: '".$input. " in " .$name.' failed'
                );
            } else {
                $this->assertEquals(
                    $expected,
                    $arr['data']['conversation']['output'],
                    "Sent: ".$input. ' in ' .$name.' failed'
                );
            }
    }




    public function inputOutput()
    {
        $this->refreshApplication();

        return [


            'test line:'.__LINE__=>['unittest hello', 'unittest hello','Hi!'],
            'test line:'.__LINE__=>['unittest What did you say', 'unittest What did you say','I said Hi!'],
            'test line:'.__LINE__=>['unittest What did you say 2', 'unittest What did you say 2','I said I said Hi!'],
            'test line:'.__LINE__=>['unittest What did you say 3', 'unittest What did you say 3','I said Hi!'],
            'test line:'.__LINE__=>['unittest bot', 'unittest bot','bot test Dilly'],
            'test line:'.__LINE__=>['unittest template', 'unittest template','template test'],
            'test line:'.__LINE__=>['unittest date', 'unittest date','date test '.date('F d Y')],
            'test line:'.__LINE__=>['unittest sentence', 'unittest sentence','This is in a Sentence tag'],
            'test line:'.__LINE__=>['unittest formal', 'unittest formal','This Is A Sentence Which Has Been Formatted Formally'],
            'test line:'.__LINE__=>['unittest uppercase', 'unittest uppercase','This should be UPPERCASE'],
            'test line:'.__LINE__=>['unittest lowercase', 'unittest lowercase','This should be lowercase'],
            'test line:'.__LINE__=>['unittest version', 'unittest version','test '.config('lemur_version.bot.id')],
            'test line:'.__LINE__=>['unittest program', 'unittest program','test '.config('lemur_version.bot.id')],
            'test line:'.__LINE__=>['unittest id', 'unittest id','string'], //this is regenerated every time so lets just check its a string
            'test line:'.__LINE__=>['unittest srai', 'unittest srai','srai test template test'],
            'test line:'.__LINE__=>['unittest sr', 'unittest sr','sr test template test'],
            'test line:'.__LINE__=>['unittest my favorite food is noodles',
                'unittest My favorite food is noodles','Your favorite food is noodles'],
            'test line:'.__LINE__=>['unittest big juicy ramen robot disco code and then some',
                'unittest big juicy ramen robot disco code and then some',
                '1: ramen. 2: robot. 3: disco. 4: code. 5: and then some.'],
            'test line:'.__LINE__=>['unittest start one two three four five', 'unittest start one two three four five',
                'End five four three two one'],
            'test line:'.__LINE__=>['unittest li 1', 'unittest li 1','test line:'.__LINE__=>['A','B','C']],
            'test line:'.__LINE__=>['unittest li 2', 'unittest li 2',"I dont know your name."],
            'test line:'.__LINE__=>['unittest my testname is bob', 'unittest My testname is Bob','Hi Bob'],
            'test line:'.__LINE__=>['unittest my testname is tony', 'unittest My testname is Tony','I will remember your name.'],
            'test line:'.__LINE__=>['unittest the topic is trees', 'unittest The topic is trees','I will remember the topic.'],
            'test line:'.__LINE__=>['unittest set', 'unittest set','1 23'],
            'test line:'.__LINE__=>['unittest set 2', 'unittest set 2','4 5 6'],
            'test line:'.__LINE__=>['unittest think', 'unittest think','This has passed.'],
            'test line:'.__LINE__=>['unittest my password is great', 'unittest my password is great','Good password: great'],
            'test line:'.__LINE__=>['unittest i am 27 how old are you', 'unittest i am 27 how old are you','Your age is 27. I am 27 aswell'],
            'test line:'.__LINE__=>['unittest my password is poo. unittest what is my password',
                'unittest my password is poo. unittest what is my password','Good password: poo. Your password is poo'],
            'test line:'.__LINE__=>['unittest i like the color blue', 'unittest i like the color blue','blue is a great testcolor'],
            'test line:'.__LINE__=>['unittest what is my age', 'unittest WHAT IS MY AGE','27'],
            'test line:'.__LINE__=>['unittest my age is 23', 'unittest MY AGE IS 23','Your age is 23'],
            'test line:'.__LINE__=>['unittest set and get age', 'unittest SET AND GET AGE','setting age: 66. You are age: 66'],
            'test line:'.__LINE__=>['unittest random', 'unittest random', ['HOO','MOO','GOO']],
            'test line:'.__LINE__=>['unittest do you find me attractive', 'unittest do you find me attractive','I find you very attractive.'],
            'test line:'.__LINE__=>['unittest what is my testname', 'unittest what is my testname','Your name is Bob'],
            'test line:'.__LINE__=>['unittest i am a girl', 'unittest i am a girl','You are a girl.'],
            'test line:'.__LINE__=>['unittest i am a girl. unittest do you find me pretty',
                'unittest i am a girl. unittest do you find me pretty','You are a girl. I find you very pretty.'],
            'test line:'.__LINE__=>['unittest i am a vegan', 'unittest i am a vegan','No you are a veggie.'],
            'test line:'.__LINE__=>['unittest i am a vegetarian', 'unittest i am a vegetarian','No you are vegan.'],
            'test line:'.__LINE__=>['unittest are you hungry', 'unittest are you hungry','I would like a veggie lasagna.'],
            'test line:'.__LINE__=>['unittest are you hungry now', 'unittest are you hungry now','I would like a apple pie.'],
            'test line:'.__LINE__=>['unittest do you want a snack', 'unittest do you want a snack','Yes I want a snack.'],
            'test line:'.__LINE__=>['unittest are you hungry again', 'unittest are you hungry again','Yes I want a snack.'],
            'test line:'.__LINE__=>['unittest are you hungry again 2', 'unittest are you hungry again 2','Yes I want a snack.'],
            'test line:'.__LINE__=>['unittest are you hungry again 3', 'unittest are you hungry again 3','I would like a apple pie.'],
            'test line:'.__LINE__=>['unittest zero test', 'unittest zero test','0'],
            'test line:'.__LINE__=>['unittest explode bob', 'unittest explode bob','b o b'],
            'test line:'.__LINE__=>['unittest name xx is', 'unittest name xx is','I have set xx to yy'],
            'test line:'.__LINE__=>['unittest name xx is set', 'unittest name xx is set','xx is yy'],
            'test line:'.__LINE__=>['unittest var oo is', 'unittest var oo is','I have set oo to pp. oo is pp'],
            'test line:'.__LINE__=>['unittest who am i now', 'unittest who am i now',["I dont know your name."]],
            'test line:'.__LINE__=>['unittest stop repeating me', 'unittest stop repeating me','unittest stop repeating me'],
            'test line:'.__LINE__=>['unittest i said stop repeating me', 'unittest stop repeating me','unittest stop repeating me'],
            'test line:'.__LINE__=>['unittest stop repeating me dummy', 'unittest stop repeating me dummy','unittest stop repeating me dummy'],
            'test line:'.__LINE__=>['unittest i request you stop repeating me',
                'unittest i request you stop repeating me','unittest i request you stop repeating me'],
            'test line:'.__LINE__=>['unittest i requested you stop repeating me',
                'unittest i requested you stop repeating me','unittest i request you stop repeating me'],
            'test line:'.__LINE__=>['unittest isn t this fun', 'unittest THIS ISN T FUN','unittest this isn\'t fun'],
            'test line:'.__LINE__=>['unittest don t cry and moan', 'unittest don t cry and moan','unittest don\'t cry & moan'],
            'test line:'.__LINE__=>['unittest 1 test normalization 1',
                'unittest 1 TEST NORMALIZATION don\'t do it & then be sad silly-head',
                'DON T DO IT THEN BE SAD SILLY HEAD'],
            'test line:'.__LINE__=>['unittest 2 test normalization 2', 'unittest 2_test_normalization_2','2 TEST NORMALIZATION 2'],
            'test line:'.__LINE__=>['unittest javascript', 'unittest javascript','[script]alert(\'hi\')[/script]'],
            'test line:'.__LINE__=>['unittest the sky is blue', 'unittest the sky is blue','I will remember that the sky is blue.'],
            'test line:'.__LINE__=>['unittest the sky is blue. unittest what color is the sky',
                'unittest the sky is blue. unittest WHAT COLOR IS THE SKY',
                'I will remember that the sky is blue. The sky is blue'],
            'test line:'.__LINE__=>['unittest I like coffee.', 'unittest I like coffee.','I will remember that you like coffee.'],
            'test line:'.__LINE__=>['unittest what do i like', 'unittest what do i like','You like coffee.'],
            'test line:'.__LINE__=>['unittest did you hear that mary loves steve',
                'unittest Did you hear that mary loves steve','Interesting gossip'],
            'test line:'.__LINE__=>['unittest Tell me some gossip', 'unittest Tell me some gossip','mary loves steve'],
            'test line:'.__LINE__=>['unittest gender test 1', 'unittest gender test 1','she told him to take a hike. So take a hike he man'],
            'test line:'.__LINE__=>['unittest does it belong to her', 'unittest does it belong to her','No, it belongs to him'],
            'test line:'.__LINE__=>['unittest does it belong to him', 'unittest does it belong to him','No, it belongs to her'],
            'test line:'.__LINE__=>['unittest i am waiting for you', 'unittest i am waiting for you','You are waiting for me'],
            'test line:'.__LINE__=>['unittest i have ordered a taxi', 'unittest i have ordered a taxi','unittest you have ordered a taxi'],
            'test line:'.__LINE__=>['unittest me', 'unittest me', 'them'],
            'test line:'.__LINE__=>['unittest give the password to me',
                'unittest give the password to me', 'User has asked me to give the password to them'],
            'test line:'.__LINE__=>['unittest lemonade let us talk about lemonade',
                'unittest lemonade let us talk about lemonade','OK, I like lemonade'],
            'test line:'.__LINE__=>['unittest lemonade let us talk about lemonade. unittest how do you take it?',
                'unittest lemonade let us talk about lemonade. unittest how do you take it?',
                'OK, I like lemonade. With ice and lemon'],
            'test line:'.__LINE__=>['unittest cola let us talk about cola', 'unittest cola let us talk about cola','OK, I like cola'],
            'test line:'.__LINE__=>['unittest cola let us talk about cola. unittest how do you take it?',
                'unittest cola let us talk about cola. unittest how do you take it?',
                'OK, I like cola. With rum and lime'],
            'test line:'.__LINE__=>['unittest how about blue cars?', 'unittest how about blue cars?','How about cars.'],
            'test line:'.__LINE__=>['unittest i like strong coffee', 'unittest i like strong coffee',
                'Do you take cream or sugar in your coffee?'],
            'test line:'.__LINE__=>['unittest yes about coffee', 'unittest yes','I do too.'],
            'test line:'.__LINE__=>['unittest i like strong coffee', 'unittest i like strong coffee',
                'Do you take cream or sugar in your coffee?'],
            'test line:'.__LINE__=>['unittest no about coffee', 'unittest no','Really? I do.'],
            'test line:'.__LINE__=>['unittest hi response', 'unittest Hi response','Hi there'],
            'test line:'.__LINE__=>['unittest i would like a drink of lemonade', 'unittest I would like a drink of lemonade',
                'Ok. You want lemonade'],
            'test line:'.__LINE__=>['unittest i would like a drink of lemonade. unittest what do I want?',
                'unittest I would like a drink of lemonade. unittest what do I want to drink?',
                'Ok. You want lemonade. lemonade'],
            'test line:'.__LINE__=>['unittest I would like a glass of cola', 'unittest I would like a glass of cola','Ok.'],
            'test line:'.__LINE__=>['unittest I would like a glass of cola. unittest what do I want?',
                'unittest I would like a glass of cola. unittest what do I want?','Ok. cola'],
            'test line:'.__LINE__=>['unittest what drink do you like', 'unittest what drink do you like', 'I like coffee'],
            'test line:'.__LINE__=>['unittest i like it too', 'unittest i like it too', 'What do you like best about coffee?'],
            'test line:'.__LINE__=>['unittest setting custom map.', 'unittest setting custom map.','CUSTOM MAP SET'],
           'test line:'.__LINE__=>['unittest what is the main city of spain?', 'unittest what is the main city of spain','madrid.'],
             'test line:'.__LINE__=>['unittest what is the capital city of england?',
                'unittest what is the capital city of england?','The capital of england is london.'],
            'test line:'.__LINE__=>['unittest what is the capital of france?',
                'unittest what is the capital of france?','The capital of france is paris.'],
            'test line:'.__LINE__=>['unittest what is the capital of france please?',
                'unittest what is the capital of france please?','The capital of france is paris.'],
            'test line:'.__LINE__=>['unittest link 1', 'unittest html link','<a href="https://www.google.com">I am a link to google</a>'],
            'test line:'.__LINE__=>['unittest link 2', 'unittest html link 2','This is a <a href="https://www.google.com">link</a> to google'],
            'test line:'.__LINE__=>['unittest teach atopic', 'unittest teach atopic',
                ["Yes, I can teach atopic. Let's talk about ATOPIC!", "I love atopic! Let's talk about ATOPIC!",
                    "Sure! Let's do it! Let's talk about ATOPIC!",
                    "Let's talk about ATOPIC","Let's talk about ATOPIC!"]],
            'test line:'.__LINE__=>['unittest teach chess. unittest file', 'unittest teach chess. unittest file',
                'Yes, I can teach chess. Let\'s talk about CHESS! Pass.'],

            'test line:'.__LINE__=>['unittest count down from 5', 'unittest count down from 5','5 4 3 2 1'],
            'test line:'.__LINE__=>['unittest count down from 3', 'unittest count down from 3','3 2 1'],

            'test line:'.__LINE__=>['unittest srai chain 3', 'unittest srai chain 3','SRAI CHAIN END'],
            'test line:'.__LINE__=>['unittest srai chain 1', 'unittest srai chain 1','SRAI CHAIN END'],
            'test line:'.__LINE__=>['unittest srai chain 10', 'unittest srai chain 10','SRAI CHAIN END'],
            'test line:'.__LINE__=>['unittest srai chain 11', 'unittest srai chain 11','Error - thinking too deeply.'],
            'test line:'.__LINE__=>['unittest srai chain 12', 'unittest srai chain 12','Error - thinking too deeply.'],
            'test line:'.__LINE__=>['unittest 26a hat found wildcard', 'unittest 26a hat found wildcard','This is the found wildcard'],
            'test line:'.__LINE__=>['unittest 26a hat wildcard', 'unittest 26a hat wildcard','This is the wildcard'],
            'test line:'.__LINE__=>['unittest 26b hat foundwildcard', 'unittest 26b hat foundwildcard','This is the found wildcard'],
            'test line:'.__LINE__=>['unittest 26b hat wildcard', 'unittest 26b hat wildcard','This is the wildcard'],
            'test line:'.__LINE__=>['unittest 26c hatfound wildcard', 'unittest 26c hatfound wildcard','This is the found wildcard'],
            'test line:'.__LINE__=>['unittest 26c hat wildcard', 'unittest 26c hat wildcard','This is the wildcard'],
            'test line:'.__LINE__=>['unittest 27a hash found wildcard', 'unittest 27a hash found wildcard','This is the found wildcard'],
            'test line:'.__LINE__=>['unittest 27a hash wildcard', 'unittest 27a hash wildcard','This is the wildcard'],
            'test line:'.__LINE__=>['unittest 27b hash foundwildcard', 'unittest 27b hash foundwildcard','This is the found wildcard'],
            'test line:'.__LINE__=>['unittest 27b hash wildcard', 'unittest 27b hash wildcard','This is the wildcard'],
            'test line:'.__LINE__=>['unittest 27c hashfound wildcard', 'unittest 27c hashfound wildcard','This is the found wildcard'],
            'test line:'.__LINE__=>['unittest 27c hash wildcard', 'unittest 27c hash wildcard','This is the wildcard'],
            'test line:'.__LINE__=>['unittest firsttag first second third',
                'unittest firsttag first second third','The first item in the star list is first'],
            'test line:'.__LINE__=>['unittest resttag first second third',
                'unittest resttag first second third','The rest of items in the star list are second third'],
            'test line:'.__LINE__=>['unittest thatstar setup p',
                'unittest thatstar setup p','Unittest setup with p'],
          'test line:'.__LINE__=>['unittest thatstar check',
                'unittest thatstar check','Test checked found p'],
              'test line:'.__LINE__=>['unittest wildcard score test 1 abc123',
                'unittest wildcard score test 1 abc123','hash # wildcard'],
            'test line:'.__LINE__=>['unittest wildcard score test 2 abc123',
                'unittest wildcard score test 2 abc123','underscore _ wildcard'],
            'test line:'.__LINE__=>['unittest wildcard score test 3 abc123',
                'unittest wildcard score test 3 abc123','hat ^ wildcard'],
            'test line:'.__LINE__=>['unittest exact match score test',
                'unittest exact match score test','exact dollar match'],
            'test line:'.__LINE__=>['unittest sr unittest hi',
                'unittest sr unittest hi','Hello!'],
            'test line:'.__LINE__=>['unittest say foreign sì vorrei una valùtazione gratuita',
                'unittest say foreign sì vorrei una valùtazione gratuita','sì vorrei una valùtazione gratuita'],


        ];
    }
    /**
     *
     */
    public function tearDown() :void
    {

        $config = app('config');
        TagStack::getInstance()->destroy();
        $this->artisan('config:clear');
        $this->artisan('cache:clear');
        $this->artisan('route:clear');
        parent::tearDown();
        app()->instance('config', $config);
    }
}
