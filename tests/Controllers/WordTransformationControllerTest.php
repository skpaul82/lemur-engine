<?php namespace Tests\Controllers;

use App\Models\WordTransformation;
use App\Models\User;
use Tests\TestCase;
use Tests\CreatesApplication;

class WordTransformationControllerTest extends TestCase
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
    public function testAdminCanViewWordTransformationList()
    {
        $response = $response = $this->actingAs($this->adminUser)->get('/wordTransformations');
        $response->assertStatus(200);
    }

    /**
     * @test store
     **/
    public function testAdminCanCreateANewWordTransformation()
    {

        $wordTransformation = factory(WordTransformation::class)->make()->toArray();
        $wordTransformation = $this->cleanPostArray($wordTransformation);
        $wordTransformation['language_id']='en';

        $response = $this->actingAs($this->adminUser)->from('wordTransformations')
            ->followingRedirects()->post('/wordTransformations', $wordTransformation);
        $response->assertStatus(200);
        $response->assertSee('Word Transformation saved successfully.');
    }

    /**
     * @test update
     **/
    public function testAdminCanEditAWordTransformation()
    {

        $wordTransformationSlug = $this->createWordTransformation($this->adminUser);

        $wordTransformationTwo = factory(WordTransformation::class)->make()->toArray();
        $wordTransformationTwo = $this->cleanPostArray($wordTransformationTwo);
        $wordTransformationTwo['language_id']='en';

        $response = $this->actingAs($this->adminUser)->from('wordTransformations')
            ->followingRedirects()->patch('/wordTransformations/'.$wordTransformationSlug, $wordTransformationTwo);
        $response->assertStatus(200);
        $response->assertSee('Word Transformation updated successfully.');
    }

    /**
     * @test delete
     **/
    public function testAdminCanDeleteAWordTransformation()
    {

        $wordTransformationSlug = $this->createWordTransformation($this->adminUser);

        $response = $this->actingAs($this->adminUser)->from('wordTransformations')->from('wordTransformations')
            ->followingRedirects()->delete(route('wordTransformations.destroy', $wordTransformationSlug));
        $response->assertStatus(200);

        $response->assertSee('Word Transformation deleted successfully.');
    }


    /**
     * @test index
     **/
    public function testAuthorCannotViewWordTransformationList()
    {
        $response = $response = $this->actingAs($this->normalUser)->get('/wordTransformations');
        $response->assertStatus(403);
    }

    /**
     * @test store
     **/
    public function testAuthorCannotCreateANewWordTransformation()
    {

        $wordTransformation = factory(WordTransformation::class)->make()->toArray();
        $wordTransformation = $this->cleanPostArray($wordTransformation);
        $wordTransformation['language_id']='en';

        $response = $this->actingAs($this->normalUser)->from('wordTransformations')
            ->followingRedirects()->post('/wordTransformations', $wordTransformation);
        $response->assertStatus(403);
    }

    /**
     * @test update
     **/
    public function testAuthorCannotEditAWordTransformation()
    {

        $wordTransformationSlug = $this->createWordTransformation($this->normalUser);

        $wordTransformationTwo = factory(WordTransformation::class)->make()->toArray();
        $wordTransformationTwo = $this->cleanPostArray($wordTransformationTwo);
        $wordTransformationTwo['language_id']='en';

        $response = $this->actingAs($this->normalUser)->from('wordTransformations')
            ->followingRedirects()->patch('/wordTransformations/'.$wordTransformationSlug, $wordTransformationTwo);
        $response->assertStatus(403);
    }

    /**
     * @test delete
     **/
    public function testAuthorCannotDeleteAWordTransformation()
    {

        $wordTransformationSlug = $this->createWordTransformation($this->normalUser);

        $response = $this->actingAs($this->normalUser)->from('wordTransformations')->from('wordTransformations')
            ->followingRedirects()->delete(route('wordTransformations.destroy', $wordTransformationSlug));
        $response->assertStatus(403);
    }


    /*
* a helper function to create a set as the logged in user and return a column if specified
*/
    public function createWordTransformation($user, $column = 'slug')
    {

        $this->be($user);

        $item = factory(WordTransformation::class)->create(['language_id'=>1]);
        if ($column) {
            return $item->$column;
        } else {
            return $item;
        }
    }


    public function cleanPostArray($arr)
    {

        unset($arr['slug']);
        unset($arr['language_id']);
        unset($arr['created_at']);
        unset($arr['updated_at']);
        unset($arr['user_id']);
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
}
