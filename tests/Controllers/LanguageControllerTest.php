<?php namespace Tests\Controllers;

use App\Models\Language;
use App\Models\User;
use App\Repositories\LanguageRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\ApiTestTrait;
use Tests\TestCase;
use Tests\CreatesApplication;

class LanguageControllerTest extends TestCase
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
    public function testAdminCanViewLanguageList()
    {
        $response = $response = $this->actingAs($this->adminUser)->get('/languages');
        $response->assertStatus(200);
    }

    /**
     * @test store
     **/
    public function testAdminCanCreateANewLanguage()
    {

        $language = factory(Language::class)->make()->toArray();
        $language = $this->cleanPostArray($language);


        $response = $this->actingAs($this->adminUser)->from('/languages')
            ->followingRedirects()->post('/languages', $language);
        $response->assertStatus(200);
        $response->assertSee('Language saved successfully.');
    }

    /**
     * @test update
     **/
    public function testAdminCanEditALanguage()
    {

        $languageOne = factory(Language::class, 1)->create();
        $languageOneSlug = $languageOne[0]->slug;

        $languageTwo = factory(Language::class)->make()->toArray();
        $languageTwo = $this->cleanPostArray($languageTwo);

        $response = $this->actingAs($this->adminUser)->from('languages')
            ->followingRedirects()->patch('/languages/'.$languageOneSlug, $languageTwo);
        $response->assertStatus(200);
        $response->assertSee('Language updated successfully.');
    }

    /**
     * @test delete
     **/
    public function testAdminCanDeleteALanguage()
    {

        $language = factory(Language::class, 1)->create();
        $languageSlug = $language[0]->slug;


        $response = $this->actingAs($this->adminUser)->from('languages')
            ->followingRedirects()->delete(route('languages.destroy', $languageSlug));
        $response->assertStatus(200);

        $response->assertSee('Language deleted successfully.');
    }


    /**
     * @test index
     **/
    public function testAuthorCannotViewLanguageList()
    {
        $response = $response = $this->actingAs($this->normalUser)->get('/languages');
        $response->assertStatus(200);
    }

    /**
     * @test store
     **/
    public function testAuthorCannotCreateANewLanguage()
    {

        $language = factory(Language::class)->make()->toArray();
        $language = $this->cleanPostArray($language);

        $response = $this->actingAs($this->normalUser)->from('languages')
            ->followingRedirects()->post('/languages', $language);
        $response->assertStatus(403);
    }

    /**
     * @test update
     **/
    public function testAuthorCannotEditALanguage()
    {

        $languageOne = factory(Language::class, 1)->create();
        $languageOneSlug = $languageOne[0]->slug;

        $languageTwo = factory(Language::class)->make()->toArray();
        $languageTwo = $this->cleanPostArray($languageTwo);

        $response = $this->actingAs($this->normalUser)->from('languages')
            ->followingRedirects()->patch('/languages/'.$languageOneSlug, $languageTwo);
        $response->assertStatus(403);
    }

    /**
     * @test delete
     **/
    public function testAuthorCannotDeleteALanguage()
    {

        $language = factory(Language::class, 1)->create();
        $languageSlug = $language[0]->slug;


        $response = $this->actingAs($this->normalUser)->from('languages')
            ->followingRedirects()->delete(route('languages.destroy', $languageSlug));
        $response->assertStatus(403);
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
