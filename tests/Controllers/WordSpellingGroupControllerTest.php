<?php namespace Tests\Controllers;

use App\Models\WordSpellingGroup;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\CreatesApplication;

class WordSpellingGroupControllerTest extends TestCase
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
    public function testAdminCanViewWordSpellingGroupList()
    {
        $response = $response = $this->actingAs($this->adminUser)->get('/wordSpellingGroups');
        $response->assertStatus(200);
    }

    /**
     * @test store
     **/
    public function testAdminCanCreateANewWordSpellingGroup()
    {

        $wordSpellingGroup = factory(WordSpellingGroup::class)->make()->toArray();
        $wordSpellingGroup = $this->cleanPostArray($wordSpellingGroup);
        $wordSpellingGroup['language_id']='en';

        $response = $this->actingAs($this->adminUser)->from('/wordSpellingGroups')
            ->followingRedirects()->post('/wordSpellingGroups', $wordSpellingGroup);
        $response->assertStatus(200);
        $response->assertSee('Word Spelling Group saved successfully.');
    }

    /**
     * @test update
     **/
    public function testAdminCanEditAWordSpellingGroup()
    {

        $wordSpellingGroupSlug = $this->createWordSpellingGroup($this->adminUser);

        $wordSpellingGroupTwo = factory(WordSpellingGroup::class)->make()->toArray();
        $wordSpellingGroupTwo = $this->cleanPostArray($wordSpellingGroupTwo);
        $wordSpellingGroupTwo['language_id']='en';

        $response = $this->actingAs($this->adminUser)->from('wordSpellingGroups')
            ->followingRedirects()->patch('/wordSpellingGroups/'.$wordSpellingGroupSlug, $wordSpellingGroupTwo);
        $response->assertStatus(200);
        $response->assertSee('Word Spelling Group updated successfully.');
    }

    /**
     * @test delete
     **/
    public function testAdminCanDeleteAWordSpellingGroup()
    {

        $wordSpellingGroupSlug = $this->createWordSpellingGroup($this->adminUser);

        $response = $this->actingAs($this->adminUser)->from('wordSpellingGroups')
            ->followingRedirects()->delete(route('wordSpellingGroups.destroy', $wordSpellingGroupSlug));
        $response->assertStatus(200);

        $response->assertSee('Word Spelling Group deleted successfully.');
    }


    /**
     * @test index
     **/
    public function testAuthorCannotViewWordSpellingGroupList()
    {
        $response = $response = $this->actingAs($this->normalUser)->get('/wordSpellingGroups');
        $response->assertStatus(403);
    }

    /**
     * @test store
     **/
    public function testAuthorCannotCreateANewWordSpellingGroup()
    {

        $wordSpellingGroup = factory(WordSpellingGroup::class)->make()->toArray();
        $wordSpellingGroup = $this->cleanPostArray($wordSpellingGroup);
        $wordSpellingGroup['language_id']='en';

        $response = $this->actingAs($this->normalUser)->from('/wordSpellingGroups')
            ->followingRedirects()->post('/wordSpellingGroups', $wordSpellingGroup);
        $response->assertStatus(403);
    }

    /**
     * @test update
     **/
    public function testAuthorCannotEditAWordSpellingGroup()
    {

        $wordSpellingGroupSlug = $this->createWordSpellingGroup($this->normalUser);

        $wordSpellingGroupTwo = factory(WordSpellingGroup::class)->make()->toArray();
        $wordSpellingGroupTwo = $this->cleanPostArray($wordSpellingGroupTwo);
        $wordSpellingGroupTwo['language_id']='en';

        $response = $this->actingAs($this->normalUser)->from('wordSpellingGroups')
            ->followingRedirects()->patch('/wordSpellingGroups/'.$wordSpellingGroupSlug, $wordSpellingGroupTwo);
        $response->assertStatus(403);
    }

    /**
     * @test delete
     **/
    public function testAuthorCannotDeleteAWordSpellingGroup()
    {

        $wordSpellingGroupSlug = $this->createWordSpellingGroup($this->normalUser);

        $response = $this->actingAs($this->normalUser)->from('wordSpellingGroups')
            ->followingRedirects()->delete(route('wordSpellingGroups.destroy', $wordSpellingGroupSlug));
        $response->assertStatus(403);
    }

    /*
     * a helper function to create a set as the logged in user and return a column if specified
     */
    public function createWordSpellingGroup($user, $column = 'slug')
    {

        $this->be($user);

        $item = factory(WordSpellingGroup::class)->create(['language_id'=>1]);
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
