<?php

namespace Tests\Policies;

use App\Models\Bot;
use App\Models\Client;
use App\Models\Conversation;
use App\Models\EmptyResponse;
use App\Models\Turn;
use App\Models\User;
use App\Policies\EmptyResponsePolicy;
use Tests\TestCase;
use Tests\CreatesApplication;

class EmptyResponsePolicyAuthorTest extends TestCase
{
    private $user;
    private $policy;
    private $modelItemCreatedByLoggedInUser;
    private $modelItemCreatedByAdminPublicItem;
    private $modelItemCreatedByAdmin;
    private $modelItemCreatedByDifferentAuthor;

    public function setUp() :void
    {

        parent::setUp();

        $this->policy = new EmptyResponsePolicy();

        //create an author user.....
        $this->user = factory(User::class)->create();
        $this->user->assignRole('author');

        //create an author user.....
        $admin = factory(User::class)->create();
        $admin->assignRole('admin');

        //create an author user.....
        $userTwo = factory(User::class)->create();
        $userTwo->assignRole('author');

        $this->modelItemCreatedByLoggedInUser = $this->getModelItem($this->user);
        $this->modelItemCreatedByAdmin = $this->getModelItem($admin);
        $this->modelItemCreatedByAdminPublicItem = $this->getModelItem($admin, true);
        $this->modelItemCreatedByDifferentAuthor = $this->getModelItem($userTwo);

        //set to the user we are testing
        $this->be($this->user);
    }


    /**
     * get the model item created by a specific user
     *
     * @param $user
     * @param $isPublic
     * @return Collection|\Illuminate\Database\Eloquent\Model|mixed
     */
    public function getModelItem($user, $isPublic = false)
    {
        $this->be($user);
        $bot = factory(Bot::class)->create(['language_id'=>1, 'is_public'=>$isPublic]);
        return factory(EmptyResponse::class)->create(['bot_id'=>$bot->id]);
    }

    /**
     * -----------------------------------------------------------------------
     * TEST THE VIEW POLICIES FOR ITEMS CREATED BY DIFFERENT OWNERS
     * -----------------------------------------------------------------------
     */

    /**
     * test that the user can view their own item
     */
    public function testCanViewAsAuthorOwnItem()
    {
        $response = $this->policy->view($this->user, $this->modelItemCreatedByLoggedInUser);
        $this->assertTrue($response->allowed());
    }

    /**
     * test that the user cannot view a different users item
     */
    public function testCannotViewAsAuthorDifferentAuthorItem()
    {
        $response = $this->policy->view($this->user, $this->modelItemCreatedByDifferentAuthor);
        $this->assertFalse($response->allowed());
    }

    /**
     * test that the user cannot view an item created by an admin
     */
    public function testCannotViewAsAuthorAdminItem()
    {
        $response = $this->policy->view($this->user, $this->modelItemCreatedByAdmin);
        $this->assertFalse($response->allowed());
    }

    /**
     * test that the user cannot view an admin item when is_public is true
     */
    public function testCannotViewAsAuthorAdminPublicItem()
    {
        $response = $this->policy->view($this->user, $this->modelItemCreatedByAdminPublicItem);
        $this->assertFalse($response->allowed());
    }

    /**
     * -----------------------------------------------------------------------
     * TEST THE CREATING POLICIES
     * -----------------------------------------------------------------------
     */

    /**
     * test that the user cannot create an item
     */
    public function testCannotCreateAsAuthor()
    {
        //authors cant create these... they are created by clients when they talk to the bot
        $response = $this->policy->create($this->user);
        $this->assertFalse($response->allowed());
    }

    /**
     * -----------------------------------------------------------------------
     * TEST THE UPDATE POLICIES FOR ITEMS CREATED BY DIFFERENT OWNERS
     * -----------------------------------------------------------------------
     */

    /**
     * test that the user can update their own item
     */
    public function testCanUpdateAsAuthorOwnItem()
    {
        $response = $this->policy->update($this->user, $this->modelItemCreatedByLoggedInUser);
        $this->assertTrue($response->allowed());
    }

    /**
     * test that the user cannot update a different users item
     */
    public function testCannotUpdateAsAuthorDifferentAuthorItem()
    {
        $response = $this->policy->update($this->user, $this->modelItemCreatedByDifferentAuthor);
        $this->assertFalse($response->allowed());
    }

    /**
     * test that the user cannot update an item created by an admin
     */
    public function testCannotUpdateAsAuthorAdminItem()
    {
        $response = $this->policy->update($this->user, $this->modelItemCreatedByAdmin);
        $this->assertFalse($response->allowed());
    }

    /**
     * test that the user can update an admin item when is_public is true
     */
    public function testCannotUpdateAuthorAdminPublicItem()
    {
        $response = $this->policy->update($this->user, $this->modelItemCreatedByAdminPublicItem);
        $this->assertFalse($response->allowed());
    }


    /**
     * -----------------------------------------------------------------------
     * TEST THE DELETE POLICIES FOR ITEMS CREATED BY DIFFERENT OWNERS
     * -----------------------------------------------------------------------
     */

    /**
     * test that the user can delete their own item
     */
    public function testCanDeleteAsAuthorOwnItem()
    {
        $response = $this->policy->delete($this->user, $this->modelItemCreatedByLoggedInUser);
        $this->assertTrue($response->allowed());
    }

    /**
     * test that the user cannot delete a different users item
     */
    public function testCannotDeleteAsAuthorDifferentAuthorItem()
    {
        $response = $this->policy->delete($this->user, $this->modelItemCreatedByDifferentAuthor);
        $this->assertFalse($response->allowed());
    }

    /**
     * test that the user cannot delete an item created by an admin
     */
    public function testCannotDeleteAsAuthorAdminItem()
    {
        $response = $this->policy->delete($this->user, $this->modelItemCreatedByAdmin);
        $this->assertFalse($response->allowed());
    }

    /**
     * test that the user cannot delete an admin item when is_public is true
     */
    public function testCannotDeleteAuthorAdminPublicItem()
    {
        $response = $this->policy->delete($this->user, $this->modelItemCreatedByAdminPublicItem);
        $this->assertFalse($response->allowed());
    }


    /**
     * -----------------------------------------------------------------------
     * TEST THE RESTORE POLICIES FOR ITEMS CREATED BY DIFFERENT OWNERS
     * -----------------------------------------------------------------------
     */

    /**
     * test that the user can restore their own item
     */
    public function testCanRestoreAsAuthorOwnItem()
    {
        $response = $this->policy->delete($this->user, $this->modelItemCreatedByLoggedInUser);
        $this->assertTrue($response->allowed());
    }

    /**
     * test that the user cannot restore a different users item
     */
    public function testCannotRestoreAsAuthorDifferentAuthorItem()
    {
        $response = $this->policy->delete($this->user, $this->modelItemCreatedByDifferentAuthor);
        $this->assertFalse($response->allowed());
    }

    /**
     * test that the user cannot restore an item created by an admin
     */
    public function testCannotRestoreAsAuthorAdminItem()
    {
        $response = $this->policy->delete($this->user, $this->modelItemCreatedByAdmin);
        $this->assertFalse($response->allowed());
    }

    /**
     * test that the user cannot restore an admin item when is_public is true
     */
    public function testCannotRestoreAuthorAdminPublicItem()
    {
        $response = $this->policy->delete($this->user, $this->modelItemCreatedByAdminPublicItem);
        $this->assertFalse($response->allowed());
    }


    /**
     * -----------------------------------------------------------------------
     * TEST THE FORCE DELETE POLICIES FOR ITEMS CREATED BY DIFFERENT OWNERS
     * -----------------------------------------------------------------------
     */

    /**
     * test that the user cannot force delete their own item (only admins can do this)
     */
    public function testCannotForceDeleteAsAuthor()
    {
        $response = $this->policy->forceDelete($this->user, $this->modelItemCreatedByLoggedInUser);
        $this->assertFalse($response->allowed());
    }

    /**
     * test that the user cannot force delete a different users item
     */
    public function testCannotForceDeleteAsAuthorDifferentAuthorItem()
    {
        $response = $this->policy->forceDelete($this->user, $this->modelItemCreatedByDifferentAuthor);
        $this->assertFalse($response->allowed());
    }

    /**
     * test that the user cannot force delete an item created by an admin
     */
    public function testCannotForceDeleteAsAuthorAdminItem()
    {
        $response = $this->policy->forceDelete($this->user, $this->modelItemCreatedByAdmin);
        $this->assertFalse($response->allowed());
    }

    /**
     * test that the user cannot force delete an admin item when is_public is true
     */
    public function testCannotForceDeleteAuthorAdminPublicItem()
    {
        $response = $this->policy->forceDelete($this->user, $this->modelItemCreatedByAdminPublicItem);
        $this->assertFalse($response->allowed());
    }

    /**
     * Tear Down
     */
    public function tearDown() :void
    {

        $config = app('config');
        parent::tearDown();
        app()->instance('config', $config);
    }
}
