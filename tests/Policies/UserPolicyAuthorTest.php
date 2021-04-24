<?php

namespace Tests\Policies;

use App\Models\User;
use App\Policies\UserPolicy;
use Tests\TestCase;
use Tests\CreatesApplication;

class UserPolicyAuthorTest extends TestCase
{
    private $user;
    private $websiteUser;
    private $policy;

    public function setUp() :void
    {

        parent::setUp();

        $this->policy = new UserPolicy();

        //create an author user.....
        $user = factory(User::class)->create();
        $this->user = $user;
        $this->user->assignRole('author');

        $this->websiteUser = factory(User::class)->create();

        //set to the user we are testing
        $this->be($this->user);
    }

    public function testCannotViewAnyAsAuthor()
    {
        $response = $this->policy->viewAny($this->user);
        $this->assertFalse($response->allowed());
    }

    public function testCannotViewAsAuthor()
    {
        $response = $this->policy->view($this->user, $this->websiteUser);
        $this->assertFalse($response->allowed());
    }

    public function testCannotCreateAsAuthor()
    {
        $response = $this->policy->create($this->user);
        $this->assertFalse($response->allowed());
    }

    public function testCannotUpdateAsAuthor()
    {
        $response = $this->policy->update($this->user, $this->websiteUser);
        $this->assertFalse($response->allowed());
    }

    public function testCannotDeleteAsAuthor()
    {
        $response = $this->policy->delete($this->user, $this->websiteUser);
        $this->assertFalse($response->allowed());
    }

    public function testCannotRestoreAsAuthor()
    {
        $response = $this->policy->restore($this->user, $this->websiteUser);
        $this->assertFalse($response->allowed());
    }

    public function testCannotForceDeleteAsAuthor()
    {
        $response = $this->policy->forceDelete($this->user, $this->websiteUser);
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
