<?php

namespace Tests\Policies;

use App\Models\WordTransformation;
use App\Models\User;
use App\Policies\WordTransformationPolicy;
use Tests\TestCase;
use Tests\CreatesApplication;

class WordTransformationPolicyAdminTest extends TestCase
{
    private $user;
    private $policy;
    private $wordTransformation;

    public function setUp() :void
    {

        parent::setUp();

        //create an admin user.....
        $user = factory(User::class)->create();
        $this->user = $user;
        $this->user->assignRole('admin');
        $this->be($this->user);

        $this->policy = new WordTransformationPolicy();
        $this->wordTransformation = factory(WordTransformation::class)
            ->create(['language_id'=>1]);
    }

    public function testCanViewAnyAsAdmin()
    {
        $response = $this->policy->viewAny($this->user);
        $this->assertTrue($response->allowed());
    }

    public function testCanViewAsAdmin()
    {
        $response = $this->policy->view($this->user, $this->wordTransformation);
        $this->assertTrue($response->allowed());
    }

    public function testCanCreateAsAdmin()
    {
        $response = $this->policy->create($this->user);
        $this->assertTrue($response->allowed());
    }

    public function testCanUpdateAsAdmin()
    {
        $response = $this->policy->update($this->user, $this->wordTransformation);
        $this->assertTrue($response->allowed());
    }

    public function testCanDeleteAsAdmin()
    {
        $response = $this->policy->delete($this->user, $this->wordTransformation);
        $this->assertTrue($response->allowed());
    }

    public function testCanRestoreAsAdmin()
    {
        $response = $this->policy->restore($this->user, $this->wordTransformation);
        $this->assertTrue($response->allowed());
    }

    public function testCanForceDeleteAsAdmin()
    {
        $response = $this->policy->forceDelete($this->user, $this->wordTransformation);
        $this->assertTrue($response->allowed());
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
        //$this->artisan('optimize');
        parent::tearDown();
        app()->instance('config', $config);
    }
}
