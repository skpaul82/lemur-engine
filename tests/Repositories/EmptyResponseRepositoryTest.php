<?php namespace Tests\Repositories;

use App\Models\EmptyResponse;
use App\Models\User;
use App\Repositories\EmptyResponseRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class EmptyResponseRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var EmptyResponseRepository
     */
    protected $emptyResponseRepo;
    protected $adminUser;

    public function setUp() : void
    {
        parent::setUp();
        //create an admin user.....
        $adminUser = factory(User::class, 1)->create();
        $this->adminUser = $adminUser[0];
        $this->adminUser->assignRole('admin');
        $this->emptyResponseRepo = \App::make(EmptyResponseRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateEmptyResponse()
    {
        $emptyResponse = factory(EmptyResponse::class)->make()->toArray();

        $createdEmptyResponse = $this->emptyResponseRepo->create($emptyResponse);

        $createdEmptyResponse = $createdEmptyResponse->toArray();
        $this->assertArrayHasKey('id', $createdEmptyResponse);
        $this->assertNotNull(
            $createdEmptyResponse['id'],
            'Created EmptyResponse must have id specified'
        );
        $this->assertNotNull(
            EmptyResponse::find($createdEmptyResponse['id']),
            'EmptyResponse with given id must be in DB'
        );
        $this->assertModelData($emptyResponse, $createdEmptyResponse);
    }

    /**
     * @test read
     */
    public function testReadEmptyResponse()
    {
        $emptyResponse = factory(EmptyResponse::class)->create();

        $dbEmptyResponse = $this->emptyResponseRepo->find($emptyResponse->id);

        $dbEmptyResponse = $dbEmptyResponse->toArray();
        $this->assertModelData($emptyResponse->toArray(), $dbEmptyResponse);
    }

    /**
     * @test update
     */
    public function testUpdateEmptyResponse()
    {
        $emptyResponse = factory(EmptyResponse::class)->create();
        $fakeEmptyResponse = factory(EmptyResponse::class)->make()->toArray();

        $updatedEmptyResponse = $this->emptyResponseRepo->update($fakeEmptyResponse, $emptyResponse->id);

        $this->assertModelData($fakeEmptyResponse, $updatedEmptyResponse->toArray());
        $dbEmptyResponse = $this->emptyResponseRepo->find($emptyResponse->id);
        $this->assertModelData($fakeEmptyResponse, $dbEmptyResponse->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteEmptyResponse()
    {
        $emptyResponse = factory(EmptyResponse::class)->create();

        $resp = $this->emptyResponseRepo->delete($emptyResponse->id);

        $this->assertTrue($resp);
        $this->assertNull(
            EmptyResponse::find($emptyResponse->id),
            'EmptyResponse should not exist in DB'
        );
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
