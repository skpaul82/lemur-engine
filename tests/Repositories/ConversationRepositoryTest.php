<?php namespace Tests\Repositories;

use App\Models\Conversation;
use App\Models\User;
use App\Repositories\ConversationRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class ConversationRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var ConversationRepository
     */
    protected $conversationRepo;
    protected $adminUser;

    public function setUp() : void
    {
        parent::setUp();
        //create an admin user.....
        $adminUser = factory(User::class, 1)->create();
        $this->adminUser = $adminUser[0];
        $this->adminUser->assignRole('admin');
        $this->conversationRepo = \App::make(ConversationRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateConversation()
    {
        $conversation = factory(Conversation::class)->make()->toArray();

        $createdConversation = $this->conversationRepo->create($conversation);

        $createdConversation = $createdConversation->toArray();
        $this->assertArrayHasKey('id', $createdConversation);
        $this->assertNotNull(
            $createdConversation['id'],
            'Created Conversation must have id specified'
        );
        $this->assertNotNull(
            Conversation::find($createdConversation['id']),
            'Conversation with given id must be in DB'
        );
        $this->assertModelData($conversation, $createdConversation);
    }

    /**
     * @test read
     */
    public function testReadConversation()
    {
        $conversation = factory(Conversation::class)->create();

        $dbConversation = $this->conversationRepo->find($conversation->id);

        $dbConversation = $dbConversation->toArray();
        $this->assertModelData($conversation->toArray(), $dbConversation);
    }

    /**
     * @test update
     */
    public function testUpdateConversation()
    {
        $conversation = factory(Conversation::class)->create();
        $fakeConversation = factory(Conversation::class)
            ->make(['client_id'=>$conversation->client_id, 'bot_id'=>$conversation->bot_id])->toArray();

        $updatedConversation = $this->conversationRepo->update($fakeConversation, $conversation->id);

        $this->assertModelData($fakeConversation, $updatedConversation->toArray());
        $dbConversation = $this->conversationRepo->find($conversation->id);
        $this->assertModelData($fakeConversation, $dbConversation->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteConversation()
    {
        $conversation = factory(Conversation::class)->create();

        $resp = $this->conversationRepo->delete($conversation->id);

        $this->assertTrue($resp);
        $this->assertNull(
            Conversation::find($conversation->id),
            'Conversation should not exist in DB'
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
