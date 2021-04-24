<?php namespace Tests\Controllers;

use App\Models\WordSpelling;
use App\Models\User;
use App\Models\WordSpellingGroup;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\CreatesApplication;

class WordSpellingControllerTest extends TestCase
{
    private $adminUser;
    private $normalUser;

    /**
     * In phpunit.xml the first 'test' that runs in this collection is the test to reset the db (setupDbTest.php)
     * Doing it this way saves time as there is no need to refresh the db on each controller test
     */
    public function setUp(): void
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
    public function testAdminCanViewWordSpellingList()
    {
        $response = $response = $this->actingAs($this->adminUser)->get('/wordSpellings');
        $response->assertStatus(200);
    }

    /**
     * @test store
     **/
    public function testAdminCanCreateANewWordSpelling()
    {

        $wordSpelling = factory(WordSpelling::class)->make()->toArray();
        $wordSpelling['word_spelling_group_id'] = $this->createWordSpellingGroup($this->adminUser);
        $wordSpelling = $this->cleanPostArray($wordSpelling);

        $response = $this->actingAs($this->adminUser)->followingRedirects()
            ->post('/wordSpellings', $wordSpelling);
        $response->assertStatus(200);
        $response->assertSee('Word Spelling saved successfully.');
    }

    public function createWordSpellingGroup($user, $column = 'slug')
    {

        $this->be($user);

        $item = factory(WordSpellingGroup::class)->create(['language_id' => 1]);
        if ($column) {
            return $item->$column;
        } else {
            return $item;
        }
    }

    public function cleanPostArray($arr)
    {

        unset($arr['slug']);
        unset($arr['created_at']);
        unset($arr['updated_at']);
        unset($arr['user_id']);
        return $arr;
    }

    /**
     * @test update
     **/
    public function testAdminCanEditAWordSpelling()
    {

        $originalWordSpellingGroup = $this->createWordSpellingGroup($this->adminUser, false);
        $originalWordSpelling = $this->createWordSpelling($this->adminUser, $originalWordSpellingGroup->id, false);

        $wordSpelling = factory(WordSpelling::class)->make()->toArray();
        $wordSpelling['word_spelling_group_id'] = $originalWordSpellingGroup->slug;
        $wordSpelling = $this->cleanPostArray($wordSpelling);

        $response = $this->actingAs($this->adminUser)->followingRedirects()
            ->patch('/wordSpellings/' . $originalWordSpelling->slug, $wordSpelling);
        $response->assertStatus(200);
        $response->assertSee('Word Spelling updated successfully.');
    }

    public function createWordSpelling($user, $wordSpellingGroupId, $column = 'slug')
    {

        $this->be($user);

        $item = factory(WordSpelling::class)->create(['word_spelling_group_id' => $wordSpellingGroupId]);
        if ($column) {
            return $item->$column;
        } else {
            return $item;
        }
    }

    /**
     * @test delete
     **/
    public function testAdminCanDeleteAWordSpelling()
    {

        $originalWordSpellingGroup = $this->createWordSpellingGroup($this->adminUser, false);
        $originalWordSpellingSlug = $this->createWordSpelling($this->adminUser, $originalWordSpellingGroup->id);

        $response = $this->actingAs($this->adminUser)->from('wordSpellings')
            ->followingRedirects()->delete(route('wordSpellings.destroy', $originalWordSpellingSlug));
        $response->assertStatus(200);

        $response->assertSee('Word Spelling deleted successfully.');
    }

    /**
     * @test index
     **/
    public function testAuthorCannotViewWordSpellingList()
    {
        $response = $response = $this->actingAs($this->normalUser)->get('/wordSpellings');
        $response->assertStatus(403);
    }

    /**
     * @test store
     **/
    public function testAuthorCannotCreateANewWordSpelling()
    {

        $wordSpellingGroup = $this->createWordSpellingGroup($this->normalUser, false);

        $wordSpelling = factory(WordSpelling::class)->make()->toArray();
        $wordSpelling = $this->cleanPostArray($wordSpelling);
        $wordSpelling['word_spelling_group_id'] = $wordSpellingGroup->slug;

        $response = $this->actingAs($this->normalUser)->from('/wordSpellings')
            ->followingRedirects()->post('/wordSpellings', $wordSpelling);
        $response->assertStatus(403);
    }


    /*
     * a helper function to create a set as the logged in user and return a column if specified
     */

    /**
     * @test update
     **/
    public function testAuthorCannotEditAWordSpelling()
    {

        $wordSpellingGroup = $this->createWordSpellingGroup($this->normalUser, false);

        $wordSpelling = $this->createWordSpelling($this->normalUser, $wordSpellingGroup->id, false);

        $wordSpelling = factory(WordSpelling::class)->make()->toArray();
        $wordSpelling = $this->cleanPostArray($wordSpelling);
        $wordSpelling['word_spelling_group_id'] = $wordSpellingGroup->slug;


        $wordSpellingOne = factory(WordSpelling::class, 1)
            ->create(['user_id' => $this->normalUser->id, 'word_spelling_group_id' => 1]);
        $wordSpellingOneSlug = $wordSpellingOne[0]->slug;

        $wordSpellingTwo = factory(WordSpelling::class)->make(['user_id' => $this->normalUser->id])->toArray();
        $wordSpellingTwo['word_spelling_group_id'] = $this->createWordSpellingGroup($this->adminUser);
        $wordSpellingTwo = $this->cleanPostArray($wordSpellingTwo);

        $response = $this->actingAs($this->normalUser)->from('/wordSpellings')
            ->followingRedirects()->patch('/wordSpellings/' . $wordSpellingOneSlug, $wordSpellingTwo);
        $response->assertStatus(403);
    }



    /**
     * @test delete
     **/
    public function testAuthorCannotDeleteAWordSpelling()
    {


        $wordSpelling = factory(WordSpelling::class, 1)
            ->create(['user_id' => $this->normalUser->id, 'word_spelling_group_id' => 1]);

        $wordSpellingSlug = $wordSpelling[0]->slug;


        $response = $this->actingAs($this->normalUser)->from('wordSpellings')
            ->followingRedirects()->delete(route('wordSpellings.destroy', $wordSpellingSlug));
        $response->assertStatus(403);
    }

    /**
     *
     */
    public function tearDown(): void
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
