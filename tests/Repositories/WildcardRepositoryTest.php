<?php namespace Tests\Repositories;

use App\Models\User;
use App\Models\Wildcard;
use App\Repositories\WildcardRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class WildcardRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var WildcardRepository
     */
    protected $wildcardRepo;
    protected $adminUser;

    public function setUp() : void
    {
        parent::setUp();
        //create an admin user.....
        $adminUser = factory(User::class, 1)->create();
        $this->adminUser = $adminUser[0];
        $this->adminUser->assignRole('admin');
        $this->wildcardRepo = \App::make(WildcardRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateWildcard()
    {
        $wildcard = factory(Wildcard::class)->make()->toArray();

        $createdWildcard = $this->wildcardRepo->create($wildcard);

        $createdWildcard = $createdWildcard->toArray();
        $this->assertArrayHasKey('id', $createdWildcard);
        $this->assertNotNull($createdWildcard['id'], 'Created Wildcard must have id specified');
        $this->assertNotNull(Wildcard::find($createdWildcard['id']), 'Wildcard with given id must be in DB');
        $this->assertModelData($wildcard, $createdWildcard);
    }

    /**
     * @test read
     */
    public function testReadWildcard()
    {
        $wildcard = factory(Wildcard::class)->create();

        $dbWildcard = $this->wildcardRepo->find($wildcard->id);

        $dbWildcard = $dbWildcard->toArray();
        $this->assertModelData($wildcard->toArray(), $dbWildcard);
    }

    /**
     * @test update
     */
    public function testUpdateWildcard()
    {
        $wildcard = factory(Wildcard::class)->create();
        $fakeWildcard = factory(Wildcard::class)->make()->toArray();

        $updatedWildcard = $this->wildcardRepo->update($fakeWildcard, $wildcard->id);

        $this->assertModelData($fakeWildcard, $updatedWildcard->toArray());
        $dbWildcard = $this->wildcardRepo->find($wildcard->id);
        $this->assertModelData($fakeWildcard, $dbWildcard->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteWildcard()
    {
        $wildcard = factory(Wildcard::class)->create();

        $resp = $this->wildcardRepo->delete($wildcard->id);

        $this->assertTrue($resp);
        $this->assertNull(Wildcard::find($wildcard->id), 'Wildcard should not exist in DB');
    }

    /**
     *
     */
    public function tearDown() :void
    {

        $config = app('config');
        parent::tearDown();
        app()->instance('config', $config);
    }
}
