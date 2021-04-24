<?php namespace Tests\Controllers;

use App\Models\Set;
use App\Models\SetValue;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\CreatesApplication;

class SetValueControllerTest extends TestCase
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
    public function testAdminCanViewSetValueList()
    {
        $response = $response = $this->actingAs($this->adminUser)->get('/setValues');
        $response->assertStatus(200);
    }

    /**
     * @test store
     **/
    public function testAdminCanCreateANewSetValue()
    {

        $setSlug = $this->createSet($this->adminUser);

        $setValue = factory(SetValue::class)->make()->toArray();
        $setValue['set_id']=$setSlug;
        $setValue = $this->cleanPostArray($setValue);

        $response = $this->actingAs($this->adminUser)->from('/setValues')
            ->followingRedirects()->post('/setValues', $setValue);
        $response->assertStatus(200);
        $response->assertSee('Set Value saved successfully.');
    }

    /**
     * @test update
     **/
    public function testAdminCanEditASetValue()
    {

        $set = $this->createSet($this->adminUser, false);
        $originalSetValueSlug = $this->createSetValue($this->adminUser, $set->id, 'slug');

        $setValue = factory(SetValue::class)->make()->toArray();
        $setValue['set_id']=$set->slug;
        $setValue = $this->cleanPostArray($setValue);

        $response = $this->actingAs($this->adminUser)->from('/setValues')
            ->followingRedirects()->patch('/setValues/'.$originalSetValueSlug, $setValue);
        $response->assertStatus(200);
        $response->assertSee('Set Value updated successfully.');
    }

    /**
     * @test delete
     **/
    public function testAdminCanDeleteASetValue()
    {

        $set = $this->createSet($this->adminUser, false);
        $originalSetValueSlug = $this->createSetValue($this->adminUser, $set->id, 'slug');

        $response = $this->actingAs($this->adminUser)->from('setValues')
            ->followingRedirects()->delete(route('setValues.destroy', $originalSetValueSlug));
        $response->assertStatus(200);

        $response->assertSee('Set Value deleted successfully.');
    }


    /**
     * @test index
     **/
    public function testAuthorCanViewSetValueList()
    {
        $response = $response = $this->actingAs($this->normalUser)->get('/setValues');
        $response->assertStatus(200);
    }

    /**
     * @test store
     **/
    public function testAuthorCanCreateANewSetValue()
    {

        $setSlug = $this->createSet($this->normalUser);

        $setValue = factory(SetValue::class)->make()->toArray();
        $setValue['set_id']=$setSlug;
        $setValue = $this->cleanPostArray($setValue);

        $response = $this->actingAs($this->normalUser)->from('/setValues')
            ->followingRedirects()->post('/setValues', $setValue);
        $response->assertStatus(200);
        $response->assertSee('Set Value saved successfully.');
    }

    /**
     * @test update
     **/
    public function testAuthorCanEditASetValue()
    {

        $set = $this->createSet($this->normalUser, false);
        $originalSetValueSlug = $this->createSetValue($this->normalUser, $set->id, 'slug');

        $setValue = factory(SetValue::class)->make()->toArray();
        $setValue['set_id']=$set->slug;
        $setValue = $this->cleanPostArray($setValue);

        $response = $this->actingAs($this->normalUser)->from('/setValues')
            ->followingRedirects()->patch('/setValues/'.$originalSetValueSlug, $setValue);
        $response->assertStatus(200);
        $response->assertSee('Set Value updated successfully.');
    }

    /**
     * @test delete
     **/
    public function testAuthorCanDeleteASetValue()
    {

        $set = $this->createSet($this->normalUser, false);
        $originalSetValueSlug = $this->createSetValue($this->normalUser, $set->id, 'slug');

        $response = $this->actingAs($this->normalUser)->from('setValues')
            ->followingRedirects()->delete(route('setValues.destroy', $originalSetValueSlug));
        $response->assertStatus(200);

        $response->assertSee('Set Value deleted successfully.');
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

    /*
     * a helper function to create a set as the logged in user and return a column if specified
     */
    public function createSetValue($user, $setId, $column = 'slug')
    {

        $this->be($user);

        $item = factory(SetValue::class)->create(['set_id' => $setId]);
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
