<?php namespace Tests\Controllers;

use App\Models\Set;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\CreatesApplication;

class SetControllerTest extends TestCase
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
    public function testAdminCanViewSetList()
    {
        $response = $response = $this->actingAs($this->adminUser)->get('/sets');
        $response->assertStatus(200);
    }

    /**
     * @test store
     **/
    public function testAdminCanCreateANewSet()
    {

        $set = factory(Set::class)->make()->toArray();
        $set = $this->cleanPostArray($set);

        $response = $this->actingAs($this->adminUser)->followingRedirects()->post('/sets', $set);
        $response->assertStatus(200);
        $response->assertSee('Set saved successfully.');
    }

    /**
     * @test update
     **/
    public function testAdminCanEditASet()
    {

        $setOneSlug = $this->createSet($this->adminUser);

        $setTwo = factory(Set::class)->make()->toArray();
        $setTwo = $this->cleanPostArray($setTwo);

        $response = $this->actingAs($this->adminUser)->from('/sets')
            ->followingRedirects()->patch('/sets/'.$setOneSlug, $setTwo);
        $response->assertStatus(200);
        $response->assertSee('Set updated successfully.');
    }

    /**
     * @test delete
     **/
    public function testAdminCanDeleteASet()
    {

        $setSlug = $this->createSet($this->adminUser);

        $response = $this->actingAs($this->adminUser)->from('/sets')
            ->followingRedirects()->delete(route('sets.destroy', $setSlug));
        $response->assertStatus(200);

        $response->assertSee('Set deleted successfully.');
    }


    /**
     * @test index
     **/
    public function testAuthorCannotViewSetList()
    {
        $response = $response = $this->actingAs($this->normalUser)->get('/sets');
        $response->assertStatus(200);
    }

    /**
     * @test store
     **/
    public function testAuthorCanCreateANewSet()
    {

        $set = factory(Set::class)->make()->toArray();
        $set = $this->cleanPostArray($set);

        $response = $this->actingAs($this->normalUser)->followingRedirects()->post('/sets', $set);
        $response->assertStatus(200);
    }

    /**
     * @test update
     **/
    public function testAuthorCanEditASet()
    {


        $setOneSlug = $this->createSet($this->normalUser);

        $setTwo = factory(Set::class)->make()->toArray();
        $setTwo = $this->cleanPostArray($setTwo);

        $response = $this->actingAs($this->normalUser)->from('/sets')
            ->followingRedirects()->patch('/sets/'.$setOneSlug, $setTwo);
        $response->assertStatus(200);
    }

    /**
     * @test delete
     **/
    public function testAuthorCanDeleteASet()
    {

        $setSlug = $this->createSet($this->normalUser);
        $response = $this->actingAs($this->normalUser)->from('sets')
            ->followingRedirects()->delete(route('sets.destroy', $setSlug));
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
     * a helper function to create a set as the logged in user and return a column if specified
     */
    public function createSet($user, $column = 'slug')
    {

        $this->be($user);

        $item = factory(Set::class)->create();
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
