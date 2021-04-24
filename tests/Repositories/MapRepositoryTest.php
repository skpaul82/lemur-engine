<?php namespace Tests\Repositories;

use App\Models\Map;
use App\Models\User;
use App\Repositories\MapRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class MapRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var MapRepository
     */
    protected $mapRepo;
    protected $adminUser;

    public function setUp() : void
    {
        parent::setUp();
        //create an admin user.....
        $adminUser = factory(User::class, 1)->create();
        $this->adminUser = $adminUser[0];
        $this->adminUser->assignRole('admin');
        $this->mapRepo = \App::make(MapRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateMap()
    {
        $this->be($this->adminUser);
        $map = factory(Map::class)->make(['user_id'=>$this->adminUser->id])->toArray();

        $createdMap = $this->mapRepo->create($map);

        $createdMap = $createdMap->toArray();
        $this->assertArrayHasKey('id', $createdMap);
        $this->assertNotNull($createdMap['id'], 'Created Map must have id specified');
        $this->assertNotNull(Map::find($createdMap['id']), 'Map with given id must be in DB');
        $this->assertModelData($map, $createdMap);
    }

    /**
     * @test read
     */
    public function testReadMap()
    {
        $this->be($this->adminUser);
        $map = factory(Map::class)->create();

        $dbMap = $this->mapRepo->find($map->id);

        $dbMap = $dbMap->toArray();
        $this->assertModelData($map->toArray(), $dbMap);
    }

    /**
     * @test update
     */
    public function testUpdateMap()
    {
        $this->be($this->adminUser);
        $map = factory(Map::class)->create();
        $fakeMap = factory(Map::class)->make(['user_id'=>$this->adminUser->id])->toArray();

        $updatedMap = $this->mapRepo->update($fakeMap, $map->id);

        $this->assertModelData($fakeMap, $updatedMap->toArray());
        $dbMap = $this->mapRepo->find($map->id);
        $this->assertModelData($fakeMap, $dbMap->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteMap()
    {
        $this->be($this->adminUser);
        $map = factory(Map::class)->create();

        $resp = $this->mapRepo->delete($map->id);

        $this->assertTrue($resp);
        $this->assertNull(Map::find($map->id), 'Map should not exist in DB');
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
