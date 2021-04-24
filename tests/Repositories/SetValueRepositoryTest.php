<?php namespace Tests\Repositories;

use App\Models\SetValue;
use App\Models\User;
use App\Repositories\SetValueRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class SetValueRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var SetValueRepository
     */
    protected $setValueRepo;
    protected $adminUser;

    public function setUp() : void
    {
        parent::setUp();
        //create an admin user.....
        $adminUser = factory(User::class, 1)->create();
        $this->adminUser = $adminUser[0];
        $this->adminUser->assignRole('admin');
        $this->setValueRepo = \App::make(SetValueRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateSetValue()
    {
        $this->be($this->adminUser);
        $setValue = factory(SetValue::class)->make(['user_id'=>$this->adminUser->id])->toArray();

        $createdSetValue = $this->setValueRepo->create($setValue);

        $createdSetValue = $createdSetValue->toArray();
        $this->assertArrayHasKey('id', $createdSetValue);
        $this->assertNotNull($createdSetValue['id'], 'Created SetValue must have id specified');
        $this->assertNotNull(SetValue::find($createdSetValue['id']), 'SetValue with given id must be in DB');
        $this->assertModelData($setValue, $createdSetValue);
    }

    /**
     * @test read
     */
    public function testReadSetValue()
    {
        $this->be($this->adminUser);
        $setValue = factory(SetValue::class)->create();

        $dbSetValue = $this->setValueRepo->find($setValue->id);

        $dbSetValue = $dbSetValue->toArray();
        $this->assertModelData($setValue->toArray(), $dbSetValue);
    }

    /**
     * @test update
     */
    public function testUpdateSetValue()
    {
        $this->be($this->adminUser);
        $setValue = factory(SetValue::class)->create();
        $fakeSetValue = factory(SetValue::class)->make(['user_id'=>$this->adminUser->id])->toArray();

        $updatedSetValue = $this->setValueRepo->update($fakeSetValue, $setValue->id);

        $this->assertModelData($fakeSetValue, $updatedSetValue->toArray());
        $dbSetValue = $this->setValueRepo->find($setValue->id);
        $this->assertModelData($fakeSetValue, $dbSetValue->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteSetValue()
    {
        $this->be($this->adminUser);
        $setValue = factory(SetValue::class)->create();

        $resp = $this->setValueRepo->delete($setValue->id);

        $this->assertTrue($resp);
        $this->assertNull(SetValue::find($setValue->id), 'SetValue should not exist in DB');
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
