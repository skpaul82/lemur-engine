<?php namespace Tests\Repositories;

use App\Models\BotWordSpellingGroup;
use App\Models\User;
use App\Repositories\BotWordSpellingGroupRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class BotWordSpellingGroupRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var BotWordSpellingGroupRepository
     */
    protected $botWordSpellingGroupRepo;
    protected $adminUser;

    public function setUp() : void
    {
        parent::setUp();
        //create an admin user.....
        $adminUser = factory(User::class, 1)->create();
        $this->adminUser = $adminUser[0];
        $this->adminUser->assignRole('admin');
        $this->botWordSpellingGroupRepo = \App::make(BotWordSpellingGroupRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateBotWordSpellingGroup()
    {
        $this->be($this->adminUser);
        $botWordSpellingGroup = factory(BotWordSpellingGroup::class)
            ->make(['user_id'=>$this->adminUser->id])->toArray();

        $createdBotWordSpellingGroup = $this->botWordSpellingGroupRepo->create($botWordSpellingGroup);

        $createdBotWordSpellingGroup = $createdBotWordSpellingGroup->toArray();
        $this->assertArrayHasKey('id', $createdBotWordSpellingGroup);
        $this->assertNotNull(
            $createdBotWordSpellingGroup['id'],
            'Created BotWordSpellingGroup must have id specified'
        );
        $this->assertNotNull(
            BotWordSpellingGroup::find($createdBotWordSpellingGroup['id']),
            'BotWordSpellingGroup with given id must be in DB'
        );
        $this->assertModelData($botWordSpellingGroup, $createdBotWordSpellingGroup);
    }

    /**
     * @test read
     */
    public function testReadBotWordSpellingGroup()
    {
        $this->be($this->adminUser);
        $botWordSpellingGroup = factory(BotWordSpellingGroup::class)->create();

        $dbBotWordSpellingGroup = $this->botWordSpellingGroupRepo->find($botWordSpellingGroup->id);

        $dbBotWordSpellingGroup = $dbBotWordSpellingGroup->toArray();
        $this->assertModelData($botWordSpellingGroup->toArray(), $dbBotWordSpellingGroup);
    }

    /**
     * @test update
     */
    public function testUpdateBotWordSpellingGroup()
    {
        $this->be($this->adminUser);
        $botWordSpellingGroup = factory(BotWordSpellingGroup::class)->create();
        $fakeBotWordSpellingGroup = factory(BotWordSpellingGroup::class)
            ->make(['user_id'=>$this->adminUser->id])->toArray();

        $updatedBotWordSpellingGroup = $this->botWordSpellingGroupRepo
            ->update($fakeBotWordSpellingGroup, $botWordSpellingGroup->id);

        $this->assertModelData($fakeBotWordSpellingGroup, $updatedBotWordSpellingGroup->toArray());
        $dbBotWordSpellingGroup = $this->botWordSpellingGroupRepo->find($botWordSpellingGroup->id);
        $this->assertModelData($fakeBotWordSpellingGroup, $dbBotWordSpellingGroup->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteBotWordSpellingGroup()
    {
        $this->be($this->adminUser);
        $botWordSpellingGroup = factory(BotWordSpellingGroup::class)->create();

        $resp = $this->botWordSpellingGroupRepo->delete($botWordSpellingGroup->id);

        $this->assertTrue($resp);
        $this->assertNull(
            BotWordSpellingGroup::find($botWordSpellingGroup->id),
            'BotWordSpellingGroup should not exist in DB'
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
