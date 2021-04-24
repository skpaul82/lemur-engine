<?php

namespace Tests\Policies;

use App\Models\CategoryGroup;
use App\Models\CategoryGroupGroup;
use App\Models\User;
use App\Policies\CategoryGroupPolicy;
use Tests\TestCase;
use Tests\CreatesApplication;

class CategoryGroupPolicyAuthorTest extends TestCase
{
    private $user;
    private $policy;
    private $modelItemCreatedByLoggedInUser;
    private $modelItemCreatedByAdminMasterItem;
    private $modelItemCreatedByAdmin;
    private $modelItemCreatedByDifferentAuthor;

    public function setUp() :void
    {

        parent::setUp();

        $this->policy = new CategoryGroupPolicy();

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
        $this->modelItemCreatedByAdminMasterItem = $this->getModelItem($admin, true);
        $this->modelItemCreatedByDifferentAuthor = $this->getModelItem($userTwo);

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
        return factory(CategoryGroup::class)->create(['language_id'=>1, 'is_master'=>$isMaster]);
    }

    /**
     * -----------------------------------------------------------------------
     * TEST THE VIEW POLICIES FOR LISTING ITEMS
     * -----------------------------------------------------------------------
     */

    /**
     * test that the user can view list/tables of bots
     */
    public function testCanViewAnyAsAuthor()
    {
        $response = $this->policy->viewAny($this->user);
        $this->assertTrue($response->allowed());
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
     * test that the user can view an admin item when is_master is true
     */
    public function testCanViewAsAuthorAdminMasterItem()
    {
        $response = $this->policy->view($this->user, $this->modelItemCreatedByAdminMasterItem);
        $this->assertTrue($response->allowed());
    }

    /**
     * -----------------------------------------------------------------------
     * TEST THE CREATING POLICIES
     * -----------------------------------------------------------------------
     */

    /**
     * test that the user can create an item
     */
    public function testCanCreateAsAuthor()
    {
        $response = $this->policy->create($this->user);
        $this->assertTrue($response->allowed());
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
     * test that the user can update an admin item when is_master is true
     */
    public function testCannotUpdateAuthorAdminMasterItem()
    {
        $response = $this->policy->update($this->user, $this->modelItemCreatedByAdminMasterItem);
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
     * test that the user cannot delete an admin item when is_master is true
     */
    public function testCannotDeleteAuthorAdminMasterItem()
    {
        $response = $this->policy->delete($this->user, $this->modelItemCreatedByAdminMasterItem);
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
     * test that the user cannot restore an admin item when is_master is true
     */
    public function testCannotRestoreAuthorAdminMasterItem()
    {
        $response = $this->policy->delete($this->user, $this->modelItemCreatedByAdminMasterItem);
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
     * test that the user cannot force delete an admin item when is_master is true
     */
    public function testCannotForceDeleteAuthorAdminMasterItem()
    {
        $response = $this->policy->forceDelete($this->user, $this->modelItemCreatedByAdminMasterItem);
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
