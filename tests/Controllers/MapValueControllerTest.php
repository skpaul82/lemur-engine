<?php namespace Tests\Controllers;

use App\Models\Map;
use App\Models\MapValue;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\CreatesApplication;

class MapValueControllerTest extends TestCase
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
    public function testAdminCanViewMapValueList()
    {
        $response = $response = $this->actingAs($this->adminUser)->get('/mapValues');
        $response->assertStatus(200);
    }

    /**
     * @test store
     **/
    public function testAdminCanCreateANewMapValue()
    {

        $mapSlug = $this->createMap($this->adminUser);

        $mapValue = factory(MapValue::class)->make()->toArray();
        $mapValue['map_id']=$mapSlug;
        $mapValue = $this->cleanPostArray($mapValue);

        $response = $this->actingAs($this->adminUser)->from('/mapValues')
            ->followingRedirects()->post('/mapValues', $mapValue);
        $response->assertStatus(200);
        $response->assertSee('Map Value saved successfully.');
    }

    /**
     * @test update
     **/
    public function testAdminCanEditAMapValue()
    {

        $map = $this->createMap($this->adminUser, false);
        $originalMapValueSlug = $this->createMapValue($this->adminUser, $map->id, 'slug');

        $mapValue = factory(MapValue::class)->make()->toArray();
        $mapValue['map_id']=$map->slug;
        $mapValue = $this->cleanPostArray($mapValue);

        $response = $this->actingAs($this->adminUser)->followingRedirects()
            ->patch('/mapValues/'.$originalMapValueSlug, $mapValue);
        $response->assertStatus(200);
        $response->assertSee('Map Value updated successfully.');
    }

    /**
     * @test delete
     **/
    public function testAdminCanDeleteAMapValue()
    {

        $map = $this->createMap($this->adminUser, false);
        $originalMapValueSlug = $this->createMapValue($this->adminUser, $map->id, 'slug');

        $response = $this->actingAs($this->adminUser)->from('mapValues')
            ->followingRedirects()->delete(route('mapValues.destroy', $originalMapValueSlug));
        $response->assertStatus(200);

        $response->assertSee('Map Value deleted successfully.');
    }


    /**
     * @test index
     **/
    public function testAuthorCanViewMapValueList()
    {
        $response = $response = $this->actingAs($this->normalUser)->get('/mapValues');
        $response->assertStatus(200);
    }

    /**
     * @test store
     **/
    public function testAuthorCanCreateANewMapValue()
    {

        $mapSlug = $this->createMap($this->normalUser);

        $mapValue = factory(MapValue::class)->make()->toArray();
        $mapValue['map_id']=$mapSlug;
        $mapValue = $this->cleanPostArray($mapValue);

        $response = $this->actingAs($this->normalUser)->from('/mapValues')
            ->followingRedirects()->post('/mapValues', $mapValue);
        $response->assertStatus(200);
        $response->assertSee('Map Value saved successfully.');
    }

    /**
     * @test update
     **/
    public function testAuthorCanEditAMapValue()
    {

        $map = $this->createMap($this->normalUser, false);
        $originalMapValueSlug = $this->createMapValue($this->normalUser, $map->id, 'slug');

        $mapValue = factory(MapValue::class)->make()->toArray();
        $mapValue['map_id']=$map->slug;
        $mapValue = $this->cleanPostArray($mapValue);

        $response = $this->actingAs($this->normalUser)->followingRedirects()
            ->patch('/mapValues/'.$originalMapValueSlug, $mapValue);
        $response->assertStatus(200);
        $response->assertSee('Map Value updated successfully.');
    }

    /**
     * @test delete
     **/
    public function testAuthorCanDeleteAMapValue()
    {

        $map = $this->createMap($this->normalUser, false);
        $originalMapValueSlug = $this->createMapValue($this->normalUser, $map->id, 'slug');

        $response = $this->actingAs($this->normalUser)->from('mapValues')
            ->followingRedirects()->delete(route('mapValues.destroy', $originalMapValueSlug));
        $response->assertStatus(200);

        $response->assertSee('Map Value deleted successfully.');
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

    /*
     * a helper function to create a map as the logged in user and return a column if specified
     */
    public function createMapValue($user, $mapId, $column = 'slug')
    {

        $this->be($user);

        $item = factory(MapValue::class)->create(['map_id' => $mapId]);
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
