<?php namespace Tests\Repositories;

use App\Models\BotCategoryGroup;
use App\Models\User;
use App\Repositories\BotCategoryGroupRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class BotCategoryGroupRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var BotCategoryGroupRepository
     */
    protected $botCategoryGroupRepo;
    protected $adminUser;

    public function setUp() : void
    {
        parent::setUp();
        //create an admin user.....
        $adminUser = factory(User::class, 1)->create();
        $this->adminUser = $adminUser[0];
        $this->adminUser->assignRole('admin');

        $this->botCategoryGroupRepo = \App::make(BotCategoryGroupRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateBotCategoryGroup()
    {
        $this->be($this->adminUser);
        $botCategoryGroup = factory(BotCategoryGroup::class)->make(['user_id'=>$this->adminUser->id])->toArray();
        $createdBotCategoryGroup = $this->botCategoryGroupRepo->create($botCategoryGroup);
        $createdBotCategoryGroup = $createdBotCategoryGroup->toArray();
        $this->assertArrayHasKey('id', $createdBotCategoryGroup);
        $this->assertNotNull(
            $createdBotCategoryGroup['id'],
            'Created BotCategoryGroup must have id specified'
        );
        $this->assertNotNull(
            BotCategoryGroup::find($createdBotCategoryGroup['id']),
            'BotCategoryGroup with given id must be in DB'
        );
        $this->assertModelData($botCategoryGroup, $createdBotCategoryGroup);
    }


    /**
     * @test read
     */
    public function testReadBotCategoryGroup()
    {
        $this->be($this->adminUser);
        $botCategoryGroup = factory(BotCategoryGroup::class)->create();
        $dbBotCategoryGroup = $this->botCategoryGroupRepo->find($botCategoryGroup->id);
        $dbBotCategoryGroup = $dbBotCategoryGroup->toArray();
        $this->assertModelData($botCategoryGroup->toArray(), $dbBotCategoryGroup);
    }

    /**
     * @test update
     */
    public function testUpdateBotCategoryGroup()
    {
        $this->be($this->adminUser);
        $botCategoryGroup = factory(BotCategoryGroup::class)->create();
        $fakeBotCategoryGroup = factory(BotCategoryGroup::class)->make()->toArray();
        $updatedBotCategoryGroup = $this->botCategoryGroupRepo->update($fakeBotCategoryGroup, $botCategoryGroup->id);
        $this->assertModelData($fakeBotCategoryGroup, $updatedBotCategoryGroup->toArray());
        $dbBotCategoryGroup = $this->botCategoryGroupRepo->find($botCategoryGroup->id);
        $this->assertModelData($fakeBotCategoryGroup, $dbBotCategoryGroup->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteBotCategoryGroup()
    {
        $this->be($this->adminUser);
        $botCategoryGroup = factory(BotCategoryGroup::class)->create();
        $resp = $this->botCategoryGroupRepo->delete($botCategoryGroup->id);
        $this->assertTrue($resp);
        $this->assertNull(
            BotCategoryGroup::find($botCategoryGroup->id),
            'BotCategoryGroup should not exist in DB'
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
