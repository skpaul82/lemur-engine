<?php

namespace Tests\Policies;

use App\Models\Language;
use App\Models\User;
use App\Policies\LanguagePolicy;
use Tests\TestCase;
use Tests\CreatesApplication;

class LanguagePolicyAuthorTest extends TestCase
{
    private $user;
    private $policy;
    private $language;

    public function setUp() :void
    {

        parent::setUp();

        //create an author user.....
        $user = factory(User::class)->create();
        $this->user = $user;
        $this->user->assignRole('author');

        $this->policy = new LanguagePolicy();
        $this->language = factory(Language::class)->create();

        //set to the user we are testing
        $this->be($this->user);
    }

    public function testCanViewAnyAsAuthor()
    {
        $response = $this->policy->viewAny($this->user);
        $this->assertTrue($response->allowed());
    }

    public function testCanViewAsAuthor()
    {
        $response = $this->policy->view($this->user, $this->language);
        $this->assertTrue($response->allowed());
    }

    public function testCannotCreateAsAuthor()
    {
        $response = $this->policy->create($this->user);
        $this->assertFalse($response->allowed());
    }

    public function testCannotUpdateAsAuthor()
    {
        $response = $this->policy->update($this->user, $this->language);
        $this->assertFalse($response->allowed());
    }

    public function testCannotDeleteAsAuthor()
    {
        $response = $this->policy->delete($this->user, $this->language);
        $this->assertFalse($response->allowed());
    }

    public function testCannotRestoreAsAuthor()
    {
        $response = $this->policy->restore($this->user, $this->language);
        $this->assertFalse($response->allowed());
    }

    public function testCannotForceDeleteAsAuthor()
    {
        $response = $this->policy->forceDelete($this->user, $this->language);
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
