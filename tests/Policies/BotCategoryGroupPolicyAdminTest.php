<?php

namespace Tests\Policies;

use App\Models\Bot;
use App\Models\BotCategoryGroup;
use App\Models\CategoryGroup;
use App\Models\User;
use App\Policies\BotCategoryGroupPolicy;
use Tests\TestCase;
use Tests\CreatesApplication;

class BotCategoryGroupPolicyAdminTest extends TestCase
{
    private $user;
    private $policy;
    private $botCategoryGroup;

    public function setUp() :void
    {

        parent::setUp();

        //create an admin user.....
        $user = factory(User::class)->create();
        $this->user = $user;
        $this->user->assignRole('admin');
        $this->be($this->user);

        $this->policy = new BotCategoryGroupPolicy();

        $bot = factory(Bot::class)->create(['language_id'=>1, 'user_id'=>$user->id]);
        $categoryGroup = factory(CategoryGroup::class)
            ->create(['language_id'=>1, 'user_id'=>$user->id]);
        $this->botCategoryGroup = factory(BotCategoryGroup::class)
            ->create(['user_id'=>$user->id, 'bot_id'=>$bot->id,'category_group_id'=>$categoryGroup->id]);
    }

    public function testCanViewAnyAsAdmin()
    {
        $response = $this->policy->viewAny($this->user);
        $this->assertTrue($response->allowed());
    }

    public function testCanViewAsAdmin()
    {
        $response = $this->policy->view($this->user, $this->botCategoryGroup);
        $this->assertTrue($response->allowed());
    }

    public function testCanCreateAsAdmin()
    {
        $response = $this->policy->create($this->user);
        $this->assertTrue($response->allowed());
    }

    public function testCanUpdateAsAdmin()
    {
        $response = $this->policy->update($this->user, $this->botCategoryGroup);
        $this->assertTrue($response->allowed());
    }

    public function testCanDeleteAsAdmin()
    {
        $response = $this->policy->delete($this->user, $this->botCategoryGroup);
        $this->assertTrue($response->allowed());
    }

    public function testCanRestoreAsAdmin()
    {
        $response = $this->policy->restore($this->user, $this->botCategoryGroup);
        $this->assertTrue($response->allowed());
    }

    public function testCanForceDeleteAsAdmin()
    {
        $response = $this->policy->forceDelete($this->user, $this->botCategoryGroup);
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
