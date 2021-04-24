<?php namespace Tests\Controllers;

use App\Models\Map;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\CreatesApplication;

class MapControllerTest extends TestCase
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
    public function testAdminCanViewMapList()
    {
        $response = $response = $this->actingAs($this->adminUser)->get('/maps');
        $response->assertStatus(200);
    }

    /**
     * @test store
     **/
    public function testAdminCanCreateANewMap()
    {

        $map = factory(Map::class)->make()->toArray();
        $map = $this->cleanPostArray($map);

        $response = $this->actingAs($this->adminUser)->followingRedirects()->post('/maps', $map);
        $response->assertStatus(200);
        $response->assertSee('Map saved successfully.');
    }

    /**
     * @test update
     **/
    public function testAdminCanEditAMap()
    {

        $mapOneSlug = $this->createMap($this->adminUser);

        $mapTwo = factory(Map::class)->make()->toArray();
        $mapTwo = $this->cleanPostArray($mapTwo);

        $response = $this->actingAs($this->adminUser)->from('/maps')
            ->followingRedirects()->patch('/maps/'.$mapOneSlug, $mapTwo);
        $response->assertStatus(200);
        $response->assertSee('Map updated successfully.');
    }

    /**
     * @test delete
     **/
    public function testAdminCanDeleteAMap()
    {

        $mapSlug = $this->createMap($this->adminUser);

        $response = $this->actingAs($this->adminUser)->from('/maps')
            ->followingRedirects()->delete(route('maps.destroy', $mapSlug));
        $response->assertStatus(200);

        $response->assertSee('Map deleted successfully.');
    }


    /**
     * @test index
     **/
    public function testAuthorCannotViewMapList()
    {
        $response = $response = $this->actingAs($this->normalUser)->get('/maps');
        $response->assertStatus(200);
    }

    /**
     * @test store
     **/
    public function testAuthorCanCreateANewMap()
    {

        $map = factory(Map::class)->make()->toArray();
        $map = $this->cleanPostArray($map);

        $response = $this->actingAs($this->normalUser)->followingRedirects()->post('/maps', $map);
        $response->assertStatus(200);
    }

    /**
     * @test update
     **/
    public function testAuthorCanEditAMap()
    {


        $mapOneSlug = $this->createMap($this->normalUser);

        $mapTwo = factory(Map::class)->make()->toArray();
        $mapTwo = $this->cleanPostArray($mapTwo);

        $response = $this->actingAs($this->normalUser)->from('/maps')
            ->followingRedirects()->patch('/maps/'.$mapOneSlug, $mapTwo);
        $response->assertStatus(200);
    }

    /**
     * @test delete
     **/
    public function testAuthorCanDeleteAMap()
    {

        $mapSlug = $this->createMap($this->normalUser);
        $response = $this->actingAs($this->normalUser)->from('maps')
            ->followingRedirects()->delete(route('maps.destroy', $mapSlug));
        $response->assertStatus(200);
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
     * a helper function to create a map as the logged in user and return a column if specified
     */
    public function createMap($user, $column = 'slug')
    {

        $this->be($user);

        $item = factory(Map::class)->create();
        if ($column) {
            return $item->$column;
        } else {
            return $item;
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
