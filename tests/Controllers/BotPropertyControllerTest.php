<?php namespace Tests\Controllers;

use App\Models\Bot;
use App\Models\BotProperty;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\CreatesApplication;

class BotPropertyControllerTest extends TestCase
{
    private $adminUser;
    private $normalUser;

    /**
     * In phpunit.xml the first 'test' that runs in this collection is the test to reset the db (setupDbTest.php)
     * Doing it this way saves time as there is no need to refresh the db on each controller test
     */
    public function setUp() :void
    {

        parent::setUp();

        //create an admin user.....
        $adminUser = factory(User::class, 1)->create();
        $this->adminUser = $adminUser[0];
        $this->adminUser->assignRole('admin');

        //create an normal user.....
        $normalUser = factory(User::class, 1)->create();
        $this->normalUser = $normalUser[0];
        $this->normalUser->assignRole('author');
    }


    /**
     * @test index
     **/
    public function testAdminCanViewBotPropertyList()
    {
        $response = $response = $this->actingAs($this->adminUser)->get('/botProperties');
        $response->assertStatus(200);
    }

    /**
     * @test store
     **/
    public function testAdminCanCreateANewBotProperty()
    {

        $botProperty = factory(BotProperty::class)->make()->toArray();
        $botProperty['bot_id']=$this->createNewBotAndGetValue($this->adminUser);
        $botProperty = $this->cleanPostArray($botProperty);

        $response = $this->actingAs($this->adminUser)->followingRedirects()->post('/botProperties', $botProperty);
        $response->assertStatus(200);
        $response->assertSee('Bot Property saved successfully.');
    }

    /**
     * @test update
     **/
    public function testAdminCanEditABotProperty()
    {

        //create a new bot directly in the db and get the slug
        $bot=$this->createNewBotAndGetValue($this->adminUser, false);

        //create a new bot property directly in the db and get the slug
        $originalBotPropertySlug = $this->createNewBotPropertyAndGetValue($this->adminUser, $bot->id, 'slug');
        //make an array for the update item
        $newBotPropertyArr = factory(BotProperty::class)->make()->toArray();
        //set the bot id for the slug
        $newBotPropertyArr['bot_id']=$bot->slug;
        //clean the post array
        $newBotPropertyArr = $this->cleanPostArray($newBotPropertyArr);

        $response = $this->actingAs($this->adminUser)->from('/botProperties')
            ->followingRedirects()->patch('/botProperties/'.$originalBotPropertySlug, $newBotPropertyArr);
        $response->assertStatus(200);
        $response->assertSee('Bot Property updated successfully.');
    }

    /**
     * @test delete
     **/
    public function testAdminCanDeleteABotProperty()
    {

        $botProperty = factory(BotProperty::class, 1)->create(['user_id'=>$this->adminUser->id, 'bot_id'=>1]);
        $botPropertySlug = $botProperty[0]->slug;

        $response = $this->actingAs($this->adminUser)->from('botProperties')
            ->followingRedirects()->delete(route('botProperties.destroy', $botPropertySlug));
        $response->assertStatus(200);

        $response->assertSee('Bot Property deleted successfully.');
    }



    /**
     * @test index
     **/
    public function testAuthorCanViewBotPropertyList()
    {
        $response = $response = $this->actingAs($this->normalUser)->get('/botProperties');
        $response->assertStatus(200);
    }

    /**
     * @test store
     **/
    public function testAuthorCanCreateANewBotProperty()
    {

        $botProperty = factory(BotProperty::class)->make(['user_id'=>$this->normalUser->id])->toArray();
        $botProperty['bot_id']=$this->createNewBotAndGetValue($this->adminUser);
        $botProperty = $this->cleanPostArray($botProperty);

        $response = $this->actingAs($this->normalUser)->followingRedirects()->post('/botProperties', $botProperty);
        $response->assertStatus(200);
        $response->assertSee('Bot Property saved successfully.');
    }

    /**
     * @test update
     **/
    public function testAuthorCanEditABotProperty()
    {

        //create a new bot directly in the db and get the slug
        $bot=$this->createNewBotAndGetValue($this->normalUser, false);

        //create a new bot property directly in the db and get the slug
        $originalBotPropertySlug = $this->createNewBotPropertyAndGetValue($this->normalUser, $bot->id, 'slug');
        //make an array for the update item
        $newBotPropertyArr = factory(BotProperty::class)->make(['user_id'=>$this->normalUser->id])->toArray();
        //set the bot id for the slug
        $newBotPropertyArr['bot_id']=$bot->slug;
        //clean the post array
        $newBotPropertyArr = $this->cleanPostArray($newBotPropertyArr);

        $response = $this->actingAs($this->adminUser)->from('/botProperties')
            ->followingRedirects()->patch('/botProperties/'.$originalBotPropertySlug, $newBotPropertyArr);
        $response->assertStatus(200);
        $response->assertSee('Bot Property updated successfully.');
    }

    /**
     * @test delete
     **/
    public function testAuthorCanDeleteABotProperty()
    {

        $botSlug =$this->createNewBotAndGetValue($this->normalUser, 'id');

        $botPropertySlug = $this->createNewBotPropertyAndGetValue($this->normalUser, $botSlug);

        $response = $this->actingAs($this->normalUser)->from('botProperties')
            ->followingRedirects()->delete(route('botProperties.destroy', $botPropertySlug));
        $response->assertStatus(200);

        $response->assertSee('Bot Property deleted successfully.');
    }



    public function cleanPostArray($arr)
    {

        unset($arr['slug']);
        unset($arr['created_at']);
        unset($arr['updated_at']);
        unset($arr['user_id']);
        return $arr;
    }

    /*
     * a helper function to create a bot as the logged in user and return a column if specified
     */
    public function createNewBotAndGetValue($user, $column = 'slug')
    {

        $this->be($user);
        $bot = factory(Bot::class)->create(['language_id'=>1]);

        if ($column) {
            return $bot->$column;
        } else {
            return $bot;
        }
    }

    /*
 * a helper function to create a bot property as the logged in user and return a column if specified
 */
    public function createNewBotPropertyAndGetValue($user, $botId, $column = 'slug')
    {

        $this->be($user);
        $botProperty = factory(BotProperty::class)->create(['bot_id'=>$botId]);

        if ($column) {
            return $botProperty->$column;
        } else {
            return $botProperty;
        }
    }

    /**
     *
     */
    public function tearDown() :void
    {

        $config = app('config');
        $this->artisan('config:clear');
        $this->artisan('cache:clear');
        $this->artisan('route:clear');
        ////$this->artisan('optimize');
        parent::tearDown();
        app()->instance('config', $config);
    }
}
