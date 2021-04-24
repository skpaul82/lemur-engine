<?php namespace Tests\Repositories;

use App\Models\Set;
use App\Models\User;
use App\Repositories\SetRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class SetRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var SetRepository
     */
    protected $setRepo;
    protected $adminUser;

    public function setUp() : void
    {
        parent::setUp();
        //create an admin user.....
        $adminUser = factory(User::class, 1)->create();
        $this->adminUser = $adminUser[0];
        $this->adminUser->assignRole('admin');
        $this->setRepo = \App::make(SetRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateSet()
    {
        $this->be($this->adminUser);
        $set = factory(Set::class)->make(['user_id'=>$this->adminUser->id])->toArray();

        $createdSet = $this->setRepo->create($set);

        $createdSet = $createdSet->toArray();
        $this->assertArrayHasKey('id', $createdSet);
        $this->assertNotNull($createdSet['id'], 'Created Set must have id specified');
        $this->assertNotNull(Set::find($createdSet['id']), 'Set with given id must be in DB');
        $this->assertModelData($set, $createdSet);
    }

    /**
     * @test read
     */
    public function testReadSet()
    {
        $this->be($this->adminUser);
        $set = factory(Set::class)->create();

        $dbSet = $this->setRepo->find($set->id);

        $dbSet = $dbSet->toArray();
        $this->assertModelData($set->toArray(), $dbSet);
    }

    /**
     * @test update
     */
    public function testUpdateSet()
    {
        $this->be($this->adminUser);
        $set = factory(Set::class)->create();
        $fakeSet = factory(Set::class)->make(['user_id'=>$this->adminUser->id])->toArray();

        $updatedSet = $this->setRepo->update($fakeSet, $set->id);

        $this->assertModelData($fakeSet, $updatedSet->toArray());
        $dbSet = $this->setRepo->find($set->id);
        $this->assertModelData($fakeSet, $dbSet->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteSet()
    {
        $this->be($this->adminUser);
        $set = factory(Set::class)->create();

        $resp = $this->setRepo->delete($set->id);

        $this->assertTrue($resp);
        $this->assertNull(Set::find($set->id), 'Set should not exist in DB');
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
