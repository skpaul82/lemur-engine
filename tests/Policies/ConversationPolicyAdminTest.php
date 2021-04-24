<?php

namespace Tests\Policies;

use App\Models\Bot;
use App\Models\Client;
use App\Models\Conversation;
use App\Models\User;
use App\Policies\ConversationPolicy;
use Tests\TestCase;
use Tests\CreatesApplication;

class ConversationPolicyAdminTest extends TestCase
{
    private $user;
    private $policy;
    private $conversation;

    public function setUp() :void
    {

        parent::setUp();

        //create an admin user.....
        $user = factory(User::class)->create();
        $this->user = $user;
        $this->user->assignRole('admin');
        $this->be($this->user);

        $this->policy = new ConversationPolicy();

        $bot = factory(Bot::class)->create(['language_id'=>1]);
        $client = factory(Client::class)->create(['bot_id'=>$bot->id]);
        $this->conversation = factory(Conversation::class)
            ->create(['bot_id'=>$bot->id, 'client_id'=>$client->id]);
    }

    public function testCanViewAnyAsAdmin()
    {
        $response = $this->policy->viewAny($this->user);
        $this->assertTrue($response->allowed());
    }

    public function testCanViewAsAdmin()
    {
        $response = $this->policy->view($this->user, $this->conversation);
        $this->assertTrue($response->allowed());
    }

    public function testCanCreateAsAdmin()
    {
        $response = $this->policy->create($this->user);
        $this->assertTrue($response->allowed());
    }

    public function testCanUpdateAsAdmin()
    {
        $response = $this->policy->update($this->user, $this->conversation);
        $this->assertTrue($response->allowed());
    }

    public function testCanDeleteAsAdmin()
    {
        $response = $this->policy->delete($this->user, $this->conversation);
        $this->assertTrue($response->allowed());
    }

    public function testCanRestoreAsAdmin()
    {
        $response = $this->policy->restore($this->user, $this->conversation);
        $this->assertTrue($response->allowed());
    }

    public function testCanForceDeleteAsAdmin()
    {
        $response = $this->policy->forceDelete($this->user, $this->conversation);
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
