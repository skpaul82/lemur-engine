<?php namespace Tests\Repositories;

use App\Models\ClientCategory;
use App\Models\User;
use App\Repositories\ClientCategoryRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class ClientCategoryRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var ClientCategoryRepository
     */
    protected $clientCategoryRepo;
    protected $adminUser;

    public function setUp() : void
    {
        parent::setUp();
        //create an admin user.....
        $adminUser = factory(User::class, 1)->create();
        $this->adminUser = $adminUser[0];
        $this->adminUser->assignRole('admin');
        $this->clientCategoryRepo = \App::make(ClientCategoryRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateClientCategory()
    {
        $clientCategory = factory(ClientCategory::class)->make()->toArray();

        $createdClientCategory = $this->clientCategoryRepo->create($clientCategory);

        $createdClientCategory = $createdClientCategory->toArray();
        $this->assertArrayHasKey('id', $createdClientCategory);
        $this->assertNotNull(
            $createdClientCategory['id'],
            'Created ClientCategory must have id specified'
        );
        $this->assertNotNull(
            ClientCategory::find($createdClientCategory['id']),
            'ClientCategory with given id must be in DB'
        );
        $this->assertModelData($clientCategory, $createdClientCategory);
    }

    /**
     * @test read
     */
    public function testReadClientCategory()
    {
        $clientCategory = factory(ClientCategory::class)->create();

        $dbClientCategory = $this->clientCategoryRepo->find($clientCategory->id);

        $dbClientCategory = $dbClientCategory->toArray();
        $this->assertModelData($clientCategory->toArray(), $dbClientCategory);
    }

    /**
     * @test update
     */
    public function testUpdateClientCategory()
    {
        $clientCategory = factory(ClientCategory::class)->create();
        $fakeClientCategory = factory(ClientCategory::class)
            ->make(['turn_id'=>$clientCategory->turn_id,
                'client_id'=>$clientCategory->client_id,
                'bot_id'=>$clientCategory->bot_id ])
            ->toArray();

        $updatedClientCategory = $this->clientCategoryRepo->update($fakeClientCategory, $clientCategory->id);

        $this->assertModelData($fakeClientCategory, $updatedClientCategory->toArray());
        $dbClientCategory = $this->clientCategoryRepo->find($clientCategory->id);



        $this->assertModelData($fakeClientCategory, $dbClientCategory->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteClientCategory()
    {
        $clientCategory = factory(ClientCategory::class)->create();

        $resp = $this->clientCategoryRepo->delete($clientCategory->id);

        $this->assertTrue($resp);
        $this->assertNull(ClientCategory::find($clientCategory->id), 'ClientCategory should not exist in DB');
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
