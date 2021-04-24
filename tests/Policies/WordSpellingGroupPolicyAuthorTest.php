<?php

namespace Tests\Policies;

use App\Models\WordSpellingGroup;
use App\Models\User;
use App\Policies\WordSpellingGroupPolicy;
use Tests\TestCase;
use Tests\CreatesApplication;

class WordSpellingGroupPolicyAuthorTest extends TestCase
{
    private $user;
    private $admin;
    private $policy;
    private $modelItemCreatedByLoggedInUser;
    private $modelItemCreatedByAdmin;

    public function setUp() :void
    {

        parent::setUp();

        $this->policy = new WordSpellingGroupPolicy();

        //create an author user.....
        $user = factory(User::class)->create();
        $this->user = $user;
        $this->user->assignRole('author');

        //create an admin user.....
        $admin = factory(User::class)->create();
        $this->admin = $admin;
        $this->admin->assignRole('admin');
        $this->modelItemCreatedByLoggedInUser = $this->getModelItem($this->user);
        $this->modelItemCreatedByAdmin = $this->getModelItem($admin);

        //set to the user we are testing
        $this->be($this->user);
    }

    /**
     * get the model item created by a specific user
     *
     * @param $user
     * @param $isMaster
     * @return Collection|\Illuminate\Database\Eloquent\Model|mixed
     */
    public function getModelItem($user, $isMaster = false)
    {

        $this->be($user);
        return factory(WordSpellingGroup::class)
            ->create(['language_id'=>1, 'is_master'=>$isMaster]);
    }

    public function testCanViewAnyAsAuthor()
    {
        $response = $this->policy->viewAny($this->user);
        //disallowed for now
        //$this->assertTrue($response->allowed());
        $this->assertFalse($response->allowed());
    }

    public function testCanViewAsAuthorOwnItem()
    {
        $response = $this->policy->view($this->user, $this->modelItemCreatedByLoggedInUser);
        //disallowed for now
        //$this->assertTrue($response->allowed());
        $this->assertFalse($response->allowed());
    }

    public function testCannotViewAsAuthorAdminItem()
    {
        $response = $this->policy->view($this->user, $this->modelItemCreatedByAdmin);
        $this->assertFalse($response->allowed());
    }

    public function testCanCreateAsAuthor()
    {
        $response = $this->policy->create($this->user);
        //disallowed for now
        //$this->assertTrue($response->allowed());
        $this->assertFalse($response->allowed());
    }

    public function testCanUpdateAsAuthorOwnItem()
    {
        $response = $this->policy->view($this->user, $this->modelItemCreatedByLoggedInUser);
        //disallowed for now
        //$this->assertTrue($response->allowed());
        $this->assertFalse($response->allowed());
    }

    public function testCannotUpdateAsAuthorAdminItem()
    {
        $response = $this->policy->view($this->user, $this->modelItemCreatedByAdmin);
        $this->assertFalse($response->allowed());
    }

    public function testCanDeleteAsAuthorOwnItem()
    {
        $response = $this->policy->delete($this->user, $this->modelItemCreatedByLoggedInUser);
        //disallowed for now
        //$this->assertTrue($response->allowed());
        $this->assertFalse($response->allowed());
    }

    public function testCannotDeleteAsAuthorAdminItem()
    {
        $response = $this->policy->delete($this->user, $this->modelItemCreatedByAdmin);
        $this->assertFalse($response->allowed());
    }

    public function testCanRestoreAsAuthorOwnItem()
    {
        $response = $this->policy->restore($this->user, $this->modelItemCreatedByLoggedInUser);
        //disallowed for now
        //$this->assertTrue($response->allowed());
        $this->assertFalse($response->allowed());
    }

    public function testCannotRestoreAsAuthorAdminItem()
    {
        $response = $this->policy->restore($this->user, $this->modelItemCreatedByAdmin);
        $this->assertFalse($response->allowed());
    }

    public function testCannotForceDeleteAsAuthor()
    {
        $response = $this->policy->forceDelete($this->user, $this->modelItemCreatedByLoggedInUser);
        $this->assertFalse($response->allowed());
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
