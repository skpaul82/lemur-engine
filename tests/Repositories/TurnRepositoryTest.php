<?php namespace Tests\Repositories;

use App\Models\Turn;
use App\Models\User;
use App\Repositories\TurnRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class TurnRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var TurnRepository
     */
    protected $turnRepo;
    protected $adminUser;

    public function setUp() : void
    {
        parent::setUp();
        //create an admin user.....
        $adminUser = factory(User::class, 1)->create();
        $this->adminUser = $adminUser[0];
        $this->adminUser->assignRole('admin');
        $this->turnRepo = \App::make(TurnRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateTurn()
    {
        $turn = factory(Turn::class)->make()->toArray();

        $createdTurn = $this->turnRepo->create($turn);

        $createdTurn = $createdTurn->toArray();


        $this->assertArrayHasKey('id', $createdTurn);
        $this->assertNotNull($createdTurn['id'], 'Created Turn must have id specified');
        $this->assertNotNull(Turn::find($createdTurn['id']), 'Turn with given id must be in DB');
        $this->assertModelData($turn, $createdTurn);
    }

    /**
     * @test read
     */
    public function testReadTurn()
    {
        $turn = factory(Turn::class)->create();

        $dbTurn = $this->turnRepo->find($turn->id);

        $dbTurn = $dbTurn->toArray();
        $this->assertModelData($turn->toArray(), $dbTurn);
    }

    /**
     * @test update
     */
    public function testUpdateTurn()
    {
        $turn = factory(Turn::class)->create();
        $fakeTurn = factory(Turn::class)->make()->toArray();

        $updatedTurn = $this->turnRepo->update($fakeTurn, $turn->id);

        $this->assertModelData($fakeTurn, $updatedTurn->toArray());
        $dbTurn = $this->turnRepo->find($turn->id);
        $this->assertModelData($fakeTurn, $dbTurn->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteTurn()
    {
        $turn = factory(Turn::class)->create();

        $resp = $this->turnRepo->delete($turn->id);

        $this->assertTrue($resp);
        $this->assertNull(Turn::find($turn->id), 'Turn should not exist in DB');
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
