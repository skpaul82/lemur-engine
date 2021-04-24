<?php

namespace Tests\Policies;

use App\Models\Bot;
use App\Models\Client;
use App\Models\Conversation;
use App\Models\ConversationProperty;
use App\Models\Turn;
use App\Models\User;
use App\Policies\ConversationPropertyPolicy;
use Tests\TestCase;
use Tests\CreatesApplication;

class ConversationPropertyPolicyAdminTest extends TestCase
{
    private $user;
    private $policy;
    private $conversationProperty;

    public function setUp() :void
    {

        parent::setUp();

        //create an admin user.....
        $user = factory(User::class)->create();
        $this->user = $user;
        $this->user->assignRole('admin');
        $this->be($this->user);

        $this->policy = new ConversationPropertyPolicy();

        $bot = factory(Bot::class)->create(['language_id'=>1]);
        $client = factory(Client::class)->create(['bot_id'=>$bot->id]);
        $conversation = factory(Conversation::class)
            ->create(['bot_id'=>$bot->id, 'client_id'=>$client->id]);
        $this->conversationProperty = factory(ConversationProperty::class)
            ->create(['conversation_id'=>$conversation->id]);
    }

    public function testCanViewAnyAsAdmin()
    {
        $response = $this->policy->viewAny($this->user);
        $this->assertTrue($response->allowed());
    }

    public function testCanViewAsAdmin()
    {
        $response = $this->policy->view($this->user, $this->conversationProperty);
        $this->assertTrue($response->allowed());
    }

    public function testCanCreateAsAdmin()
    {
        $response = $this->policy->create($this->user);
        $this->assertTrue($response->allowed());
    }

    public function testCanUpdateAsAdmin()
    {
        $response = $this->policy->update($this->user, $this->conversationProperty);
        $this->assertTrue($response->allowed());
    }

    public function testCanDeleteAsAdmin()
    {
        $response = $this->policy->delete($this->user, $this->conversationProperty);
        $this->assertTrue($response->allowed());
    }

    public function testCanRestoreAsAdmin()
    {
        $response = $this->policy->restore($this->user, $this->conversationProperty);
        $this->assertTrue($response->allowed());
    }

    public function testCanForceDeleteAsAdmin()
    {
        $response = $this->policy->forceDelete($this->user, $this->conversationProperty);
        $this->assertTrue($response->allowed());
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
