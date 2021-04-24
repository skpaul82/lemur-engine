<?php namespace Tests\Repositories;

use App\Models\CategoryGroup;
use App\Models\User;
use App\Repositories\CategoryGroupRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class CategoryGroupRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var CategoryGroupRepository
     */
    protected $categoryGroupRepo;
    protected $adminUser;

    public function setUp() : void
    {
        parent::setUp();
        //create an admin user.....
        $adminUser = factory(User::class, 1)->create();
        $this->adminUser = $adminUser[0];
        $this->adminUser->assignRole('admin');
        $this->categoryGroupRepo = \App::make(CategoryGroupRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateCategoryGroup()
    {
        $this->be($this->adminUser);
        $categoryGroup = factory(CategoryGroup::class)
            ->make(['user_id'=>$this->adminUser->id])->toArray();

        $createdCategoryGroup = $this->categoryGroupRepo->create($categoryGroup);

        $createdCategoryGroup = $createdCategoryGroup->toArray();
        $this->assertArrayHasKey('id', $createdCategoryGroup);
        $this->assertNotNull(
            $createdCategoryGroup['id'],
            'Created CategoryGroup must have id specified'
        );
        $this->assertNotNull(
            CategoryGroup::find($createdCategoryGroup['id']),
            'CategoryGroup with given id must be in DB'
        );
        $this->assertModelData($categoryGroup, $createdCategoryGroup);
    }

    /**
     * @test read
     */
    public function testReadCategoryGroup()
    {
        $this->be($this->adminUser);
        $categoryGroup = factory(CategoryGroup::class)->create();

        $dbCategoryGroup = $this->categoryGroupRepo->find($categoryGroup->id);

        $dbCategoryGroup = $dbCategoryGroup->toArray();
        $this->assertModelData($categoryGroup->toArray(), $dbCategoryGroup);
    }

    /**
     * @test update
     */
    public function testUpdateCategoryGroup()
    {
        $this->be($this->adminUser);
        $categoryGroup = factory(CategoryGroup::class)->create();
        $fakeCategoryGroup = factory(CategoryGroup::class)->make(['user_id'=>$this->adminUser->id])->toArray();

        $updatedCategoryGroup = $this->categoryGroupRepo->update($fakeCategoryGroup, $categoryGroup->id);

        $this->assertModelData($fakeCategoryGroup, $updatedCategoryGroup->toArray());
        $dbCategoryGroup = $this->categoryGroupRepo->find($categoryGroup->id);
        $this->assertModelData($fakeCategoryGroup, $dbCategoryGroup->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteCategoryGroup()
    {
        $this->be($this->adminUser);
        $categoryGroup = factory(CategoryGroup::class)->create();

        $resp = $this->categoryGroupRepo->delete($categoryGroup->id);

        $this->assertTrue($resp);
        $this->assertNull(CategoryGroup::find($categoryGroup->id), 'CategoryGroup should not exist in DB');
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
        parent::tearDown();
        app()->instance('config', $config);
    }
}
