<?php namespace Tests\Controllers;

use App\Models\Normalization;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\CreatesApplication;

class NormalizationControllerTest extends TestCase
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
    public function testAdminCanViewNormalizationList()
    {
        $response = $response = $this->actingAs($this->adminUser)->get('/normalizations');
        $response->assertStatus(200);
    }

    /**
     * @test store
     **/
    public function testAdminCanCreateANewNormalization()
    {

        $normalization = factory(Normalization::class)->make()->toArray();
        $normalization = $this->cleanPostArray($normalization);
        $response = $this->actingAs($this->adminUser)->from('normalizations')
            ->followingRedirects()->post('/normalizations', $normalization);
        $response->assertStatus(200);
        $response->assertSee('Normalization saved successfully.');
    }

    /**
     * @test update
     **/
    public function testAdminCanEditANormalization()
    {

        $normalizationOne = factory(Normalization::class, 1)->create(['language_id'=>1]);
        $normalizationOneSlug = $normalizationOne[0]->slug;
        $normalizationTwo = factory(Normalization::class)->make()->toArray();
        $normalizationTwo = $this->cleanPostArray($normalizationTwo);
        $response = $this->actingAs($this->adminUser)->from('normalizations')
            ->followingRedirects()->patch('/normalizations/'.$normalizationOneSlug, $normalizationTwo);
        $response->assertStatus(200);
        $response->assertSee('Normalization updated successfully.');
    }

    /**
     * @test delete
     **/
    public function testAdminCanDeleteANormalization()
    {

        $normalization = factory(Normalization::class, 1)->create(['language_id'=>1]);
        $normalizationSlug = $normalization[0]->slug;
        $response = $this->actingAs($this->adminUser)->from('normalizations')
            ->followingRedirects()->delete(route('normalizations.destroy', $normalizationSlug));
        $response->assertStatus(200);
        $response->assertSee('Normalization deleted successfully.');
    }


    /**
     * @test index
     **/
    public function testAuthorCannotViewNormalizationList()
    {
        $response = $response = $this->actingAs($this->normalUser)->get('/normalizations');
        $response->assertStatus(403);
    }

    /**
     * @test store
     **/
    public function testAuthorCannotCreateANewNormalization()
    {

        $normalization = factory(Normalization::class)->make()->toArray();
        $normalization = $this->cleanPostArray($normalization);
        $normalization['language_id']='en';
        $response = $this->actingAs($this->normalUser)->from('normalizations')
            ->followingRedirects()->post('/normalizations', $normalization);
        $response->assertStatus(403);
    }

    /**
     * @test update
     **/
    public function testAuthorCannotEditANormalization()
    {

        $normalizationOne = factory(Normalization::class, 1)->create(['language_id'=>1]);
        $normalizationOneSlug = $normalizationOne[0]->slug;
        $normalizationTwo = factory(Normalization::class)->make()->toArray();
        $normalizationTwo = $this->cleanPostArray($normalizationTwo);
        $normalizationTwo['language_id']='en';
        $response = $this->actingAs($this->normalUser)->from('normalizations')
            ->followingRedirects()->patch('/normalizations/'.$normalizationOneSlug, $normalizationTwo);
        $response->assertStatus(403);
    }

    /**
     * @test delete
     **/
    public function testAuthorCannotDeleteANormalization()
    {

        $normalization = factory(Normalization::class, 1)->create(['language_id'=>1]);
        $normalizationSlug = $normalization[0]->slug;
        $response = $this->actingAs($this->normalUser)->from('normalizations')
            ->followingRedirects()->delete(route('normalizations.destroy', $normalizationSlug));
        $response->assertStatus(403);
    }




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
}
