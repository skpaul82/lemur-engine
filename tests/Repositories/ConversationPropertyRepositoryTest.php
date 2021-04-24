<?php namespace Tests\Repositories;

use App\Models\ConversationProperty;
use App\Models\User;
use App\Repositories\ConversationPropertyRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class ConversationPropertyRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var ConversationPropertyRepository
     */
    protected $conversationPropertyRepo;
    protected $adminUser;

    public function setUp() : void
    {
        parent::setUp();
        //create an admin user.....
        $adminUser = factory(User::class, 1)->create();
        $this->adminUser = $adminUser[0];
        $this->adminUser->assignRole('admin');
        $this->conversationPropertyRepo = \App::make(ConversationPropertyRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateConversationProperty()
    {
        $conversationProperty = factory(ConversationProperty::class)->make()->toArray();

        $createdConversationProperty = $this->conversationPropertyRepo->create($conversationProperty);

        $createdConversationProperty = $createdConversationProperty->toArray();
        $this->assertArrayHasKey('id', $createdConversationProperty);
        $this->assertNotNull(
            $createdConversationProperty['id'],
            'Created ConversationProperty must have id specified'
        );
        $this->assertNotNull(
            ConversationProperty::find($createdConversationProperty['id']),
            'ConversationProperty with given id must be in DB'
        );
        $this->assertModelData($conversationProperty, $createdConversationProperty);
    }

    /**
     * @test read
     */
    public function testReadConversationProperty()
    {
        $conversationProperty = factory(ConversationProperty::class)->create();

        $dbConversationProperty = $this->conversationPropertyRepo->find($conversationProperty->id);

        $dbConversationProperty = $dbConversationProperty->toArray();
        $this->assertModelData($conversationProperty->toArray(), $dbConversationProperty);
    }

    /**
     * @test update
     */
    public function testUpdateConversationProperty()
    {
        $conversationProperty = factory(ConversationProperty::class)->create();
        $fakeConversationProperty = factory(ConversationProperty::class)->make()->toArray();

        $updatedConversationProperty = $this->conversationPropertyRepo
            ->update($fakeConversationProperty, $conversationProperty->id);

        $this->assertModelData($fakeConversationProperty, $updatedConversationProperty->toArray());
        $dbConversationProperty = $this->conversationPropertyRepo->find($conversationProperty->id);
        $this->assertModelData($fakeConversationProperty, $dbConversationProperty->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteConversationProperty()
    {
        $conversationProperty = factory(ConversationProperty::class)->create();

        $resp = $this->conversationPropertyRepo->delete($conversationProperty->id);

        $this->assertTrue($resp);
        $this->assertNull(
            ConversationProperty::find($conversationProperty->id),
            'ConversationProperty should not exist in DB'
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
