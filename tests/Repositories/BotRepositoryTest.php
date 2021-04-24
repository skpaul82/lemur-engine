<?php namespace Tests\Repositories;

use App\Models\Bot;
use App\Models\User;
use App\Repositories\BotPropertyRepository;
use App\Repositories\BotRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class BotRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var BotRepository
     */
    protected $botRepo;
    protected $adminUser;

    public function setUp() : void
    {
        parent::setUp();
        //create an admin user.....
        $adminUser = factory(User::class, 1)->create();
        $this->adminUser = $adminUser[0];
        $this->adminUser->assignRole('admin');
        $this->botRepo = \App::make(BotRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateBot()
    {
        $this->be($this->adminUser);
        $bot = factory(Bot::class)->make(['user_id'=>$this->adminUser->id])->toArray();

        $createdBot = $this->botRepo->create($bot);

        $createdBot = $createdBot->toArray();
        $this->assertArrayHasKey('id', $createdBot);
        $this->assertNotNull($createdBot['id'], 'Created Bot must have id specified');
        $this->assertNotNull(Bot::find($createdBot['id']), 'Bot with given id must be in DB');
        $this->assertModelData($bot, $createdBot);
    }

    /**
     * @test read
     */
    public function testReadBot()
    {
        $this->be($this->adminUser);
        $bot = factory(Bot::class)->create();

        $dbBot = $this->botRepo->find($bot->id);

        $dbBot = $dbBot->toArray();
        $this->assertModelData($bot->toArray(), $dbBot);
    }

    /**
     * @test update
     */
    public function testUpdateBot()
    {
        $this->be($this->adminUser);
        $bot = factory(Bot::class)->create();
        $fakeBot = factory(Bot::class)->make(['user_id'=>$this->adminUser->id])->toArray();

        $updatedBot = $this->botRepo->update($fakeBot, $bot->id);

        $this->assertModelData($fakeBot, $updatedBot->toArray());
        $dbBot = $this->botRepo->find($bot->id);
        $this->assertModelData($fakeBot, $dbBot->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteBot()
    {
        $this->be($this->adminUser);
        $bot = factory(Bot::class)->create();

        $resp = $this->botRepo->delete($bot->id);

        $this->assertTrue($resp);
        $this->assertNull(Bot::find($bot->id), 'Bot should not exist in DB');
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
