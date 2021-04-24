<?php namespace Tests\Repositories;

use App\Models\MapValue;
use App\Models\User;
use App\Repositories\MapValueRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class MapValueRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var MapValueRepository
     */
    protected $mapValueRepo;
    protected $adminUser;

    public function setUp() : void
    {
        parent::setUp();
        //create an admin user.....
        $adminUser = factory(User::class, 1)->create();
        $this->adminUser = $adminUser[0];
        $this->adminUser->assignRole('admin');
        $this->mapValueRepo = \App::make(MapValueRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateMapValue()
    {
        $this->be($this->adminUser);
        $mapValue = factory(MapValue::class)->make(['user_id'=>$this->adminUser->id])->toArray();

        $createdMapValue = $this->mapValueRepo->create($mapValue);

        $createdMapValue = $createdMapValue->toArray();
        $this->assertArrayHasKey('id', $createdMapValue);
        $this->assertNotNull($createdMapValue['id'], 'Created MapValue must have id specified');
        $this->assertNotNull(MapValue::find($createdMapValue['id']), 'MapValue with given id must be in DB');
        $this->assertModelData($mapValue, $createdMapValue);
    }

    /**
     * @test read
     */
    public function testReadMapValue()
    {
        $this->be($this->adminUser);
        $mapValue = factory(MapValue::class)->create();

        $dbMapValue = $this->mapValueRepo->find($mapValue->id);

        $dbMapValue = $dbMapValue->toArray();
        $this->assertModelData($mapValue->toArray(), $dbMapValue);
    }

    /**
     * @test update
     */
    public function testUpdateMapValue()
    {
        $this->be($this->adminUser);
        $mapValue = factory(MapValue::class)->create();
        $fakeMapValue = factory(MapValue::class)->make(['user_id'=>$this->adminUser->id])->toArray();

        $updatedMapValue = $this->mapValueRepo->update($fakeMapValue, $mapValue->id);

        $this->assertModelData($fakeMapValue, $updatedMapValue->toArray());
        $dbMapValue = $this->mapValueRepo->find($mapValue->id);
        $this->assertModelData($fakeMapValue, $dbMapValue->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteMapValue()
    {
        $this->be($this->adminUser);
        $mapValue = factory(MapValue::class)->create();

        $resp = $this->mapValueRepo->delete($mapValue->id);

        $this->assertTrue($resp);
        $this->assertNull(MapValue::find($mapValue->id), 'MapValue should not exist in DB');
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
