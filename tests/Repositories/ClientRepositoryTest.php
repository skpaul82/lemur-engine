<?php namespace Tests\Repositories;

use App\Models\Client;
use App\Models\User;
use App\Repositories\ClientRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class ClientRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var ClientRepository
     */
    protected $clientRepo;
    protected $adminUser;

    public function setUp() : void
    {
        parent::setUp();
        //create an admin user.....
        $adminUser = factory(User::class, 1)->create();
        $this->adminUser = $adminUser[0];
        $this->adminUser->assignRole('admin');
        $this->clientRepo = \App::make(ClientRepository::class);
    }

    /**
     * @test create
     */
    public function testClient()
    {
        $client = factory(Client::class)->make()->toArray();

        $createdClient = $this->clientRepo->create($client);

        $createdClient = $createdClient->toArray();
        $this->assertArrayHasKey('id', $createdClient);
        $this->assertNotNull($createdClient['id'], 'Created Client must have id specified');
        $this->assertNotNull(Client::find($createdClient['id']), 'Client with given id must be in DB');
        $this->assertModelData($client, $createdClient);
    }

    /**
     * @test read
     */
    public function testReadClient()
    {
        $client = factory(Client::class)->create();

        $dbClient = $this->clientRepo->find($client->id);

        $dbClient = $dbClient->toArray();
        $this->assertModelData($client->toArray(), $dbClient);
    }

    /**
     * @test update
     */
    public function testUpdateClient()
    {
        $client = factory(Client::class)->create();
        $fakeClient = factory(Client::class)->make(['bot_id'=>$client->bot_id])->toArray();

        $updatedClient = $this->clientRepo->update($fakeClient, $client->id);

        $this->assertModelData($fakeClient, $updatedClient->toArray());
        $dbClient = $this->clientRepo->find($client->id);
        $this->assertModelData($fakeClient, $dbClient->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteClient()
    {
        $client = factory(Client::class)->create();

        $resp = $this->clientRepo->delete($client->id);

        $this->assertTrue($resp);
        $this->assertNull(Client::find($client->id), 'Client should not exist in DB');
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
