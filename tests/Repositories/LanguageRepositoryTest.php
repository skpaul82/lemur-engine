<?php namespace Tests\Repositories;

use App\Models\Language;
use App\Models\User;
use App\Repositories\LanguageRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class LanguageRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var LanguageRepository
     */
    protected $languageRepo;
    protected $adminUser;

    public function setUp() : void
    {
        parent::setUp();
        //create an admin user.....
        $adminUser = factory(User::class, 1)->create();
        $this->adminUser = $adminUser[0];
        $this->adminUser->assignRole('admin');
        $this->languageRepo = \App::make(LanguageRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateLanguage()
    {
        $this->be($this->adminUser);
        $language = factory(Language::class)->make()->toArray();

        $createdLanguage = $this->languageRepo->create($language);

        $createdLanguage = $createdLanguage->toArray();
        $this->assertArrayHasKey('id', $createdLanguage);
        $this->assertNotNull($createdLanguage['id'], 'Created Language must have id specified');
        $this->assertNotNull(Language::find($createdLanguage['id']), 'Language with given id must be in DB');
        $this->assertModelData($language, $createdLanguage);
    }

    /**
     * @test read
     */
    public function testReadLanguage()
    {
        $this->be($this->adminUser);
        $language = factory(Language::class)->create();

        $dbLanguage = $this->languageRepo->find($language->id);

        $dbLanguage = $dbLanguage->toArray();
        $this->assertModelData($language->toArray(), $dbLanguage);
    }

    /**
     * @test update
     */
    public function testUpdateLanguage()
    {
        $this->be($this->adminUser);
        $language = factory(Language::class)->create();
        $fakeLanguage = factory(Language::class)->make()->toArray();

        $updatedLanguage = $this->languageRepo->update($fakeLanguage, $language->id);

        $this->assertModelData($fakeLanguage, $updatedLanguage->toArray());
        $dbLanguage = $this->languageRepo->find($language->id);
        $this->assertModelData($fakeLanguage, $dbLanguage->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteLanguage()
    {
        $this->be($this->adminUser);
        $language = factory(Language::class)->create();

        $resp = $this->languageRepo->delete($language->id);

        $this->assertTrue($resp);
        $this->assertNull(Language::find($language->id), 'Language should not exist in DB');
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
        parent::tearDown();
        app()->instance('config', $config);
    }
}
