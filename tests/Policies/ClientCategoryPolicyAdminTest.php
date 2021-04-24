<?php

namespace Tests\Policies;

use App\Models\Bot;
use App\Models\Client;
use App\Models\Conversation;
use App\Models\ClientCategory;
use App\Models\Turn;
use App\Models\User;
use App\Policies\ClientCategoryPolicy;
use Tests\TestCase;
use Tests\CreatesApplication;

class ClientCategoryPolicyAdminTest extends TestCase
{
    private $user;
    private $policy;
    private $clientCategory;

    public function setUp() :void
    {

        parent::setUp();

        //create an admin user.....
        $user = factory(User::class)->create();
        $this->user = $user;
        $this->user->assignRole('admin');
        $this->be($this->user);

        $this->policy = new ClientCategoryPolicy();

        $bot = factory(Bot::class)
            ->create(['language_id'=>1]);
        $client = factory(Client::class)
            ->create(['bot_id'=>$bot->id]);
        $conversation = factory(Conversation::class)
            ->create(['bot_id'=>$bot->id, 'client_id'=>$client->id]);
        $turn = factory(Turn::class)
            ->create(['category_id'=>null,'client_category_id'=>null,'conversation_id'=>$conversation->id]);

        $this->clientCategory = factory(ClientCategory::class)
            ->create(['client_id'=>$client->id,'bot_id'=>$bot->id, 'turn_id'=>$turn->id]);
    }

    public function testCanViewAnyAsAdmin()
    {
        $response = $this->policy->viewAny($this->user);
        $this->assertTrue($response->allowed());
    }

    public function testCanViewAsAdmin()
    {
        $response = $this->policy->view($this->user, $this->clientCategory);
        $this->assertTrue($response->allowed());
    }

    public function testCanCreateAsAdmin()
    {
        $response = $this->policy->create($this->user);
        $this->assertTrue($response->allowed());
    }

    public function testCanUpdateAsAdmin()
    {
        $response = $this->policy->update($this->user, $this->clientCategory);
        $this->assertTrue($response->allowed());
    }

    public function testCanDeleteAsAdmin()
    {
        $response = $this->policy->delete($this->user, $this->clientCategory);
        $this->assertTrue($response->allowed());
    }

    public function testCanRestoreAsAdmin()
    {
        $response = $this->policy->restore($this->user, $this->clientCategory);
        $this->assertTrue($response->allowed());
    }

    public function testCanForceDeleteAsAdmin()
    {
        $response = $this->policy->forceDelete($this->user, $this->clientCategory);
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
