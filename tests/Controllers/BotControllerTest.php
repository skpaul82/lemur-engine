<?php namespace Tests\Controllers;

use App\Models\Bot;
use App\Models\User;
use Tests\TestCase;

class BotControllerTest extends TestCase
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
    public function testAdminCanViewBotList()
    {
        $response = $response = $this->actingAs($this->adminUser)->get('/bots');
        $response->assertStatus(200);
    }

    /**
     * @test store
     **/
    public function testAdminCanCreateANewBot()
    {

        $bot = factory(Bot::class)->make()->toArray();
        $bot = $this->cleanPostArray($bot);

        $response = $this->actingAs($this->adminUser)->from('bots')
            ->followingRedirects()->post('/bots', $bot);
        $response->assertStatus(200);
        $response->assertSee('Bot saved successfully.');
    }

    /**
     * @test update
     **/
    public function testAdminCanEditABot()
    {

        $botSlug = $this->createBot($this->adminUser);

        $botTwo = factory(Bot::class)->make()->toArray();
        $botTwo = $this->cleanPostArray($botTwo);

        $response = $this->actingAs($this->adminUser)->from('bots')
            ->followingRedirects()->patch('/bots/'.$botSlug, $botTwo);
        $response->assertStatus(200);
        $response->assertSee('Bot updated successfully.');
    }

    /**
     * @test delete
     **/
    public function testAdminCanDeleteABot()
    {

        $botSlug = $this->createBot($this->adminUser);
        $response = $this->actingAs($this->adminUser)->from('bots')
            ->followingRedirects()->delete(route('bots.destroy', $botSlug));
        $response->assertStatus(200);
        $response->assertSee('Bot deleted successfully.');
    }


    /**
     * @test index
     **/
    public function testAuthorCanViewBotList()
    {
        $response = $response = $this->actingAs($this->normalUser)->get('/bots');
        $response->assertStatus(200);
    }

    /**
     * @test store
     **/
    public function testAuthorCanCreateANewBot()
    {

        $bot = factory(Bot::class)->make(['user_id'=>$this->normalUser->id])->toArray();
        $bot = $this->cleanPostArray($bot);


        $response = $this->actingAs($this->normalUser)->from('bots')
            ->followingRedirects()->post('/bots', $bot);
        $response->assertStatus(200);
        $response->assertSee('Bot saved successfully.');
    }

    /**
     * @test update
     **/
    public function testAuthorCanEditABot()
    {

        $botSlug = $this->createBot($this->normalUser);

        $botTwo = factory(Bot::class)->make(['user_id'=>$this->normalUser->id])->toArray();

        $botTwo = $this->cleanPostArray($botTwo);

        $response = $this->actingAs($this->normalUser)->from('bots')
            ->followingRedirects()->patch('/bots/'.$botSlug, $botTwo);
        $response->assertStatus(200);
        $response->assertSee('Bot updated successfully.');
    }

    /**
     * @test delete
     **/
    public function testAuthorCanDeleteABot()
    {

        $botSlug = $this->createBot($this->normalUser);
        $response = $this->actingAs($this->normalUser)->from('bots')
            ->followingRedirects()->delete(route('bots.destroy', $botSlug));
        $response->assertStatus(200);
        $response->assertSee('Bot deleted successfully.');
    }



    /**
     * we need to make the data the same as it is in the front end
     *
     * @param $arr
     * @return mixed
     */
    public function cleanPostArray($arr)
    {

        unset($arr['slug']);
        unset($arr['language_id']);
        unset($arr['created_at']);
        unset($arr['updated_at']);
        unset($arr['user_id']);
        $arr['language_id']='en';
        return $arr;
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

    /*
     * a helper function to create a bot as the logged in user and return a column if specified
     */
    public function createBot($user, $column = 'slug')
    {

        $this->be($user);

        $bot = factory(Bot::class)->create(['language_id'=>1]);
        if ($column) {
            return $bot->$column;
        } else {
            return $bot;
        }
    }

    public function loginWithFakeUser()
    {
        $user = new User([
            'id' => 1,
            'name' => 'testman'
        ]);

        $this->be($user);
    }
}
