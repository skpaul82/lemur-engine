<?php namespace Tests\Repositories;

use App\Models\Category;
use App\Models\User;
use App\Repositories\CategoryRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class CategoryRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var CategoryRepository
     */
    protected $categoryRepo;
    protected $adminUser;

    public function setUp() : void
    {
        parent::setUp();
        //create an admin user.....
        $adminUser = factory(User::class, 1)->create();
        $this->adminUser = $adminUser[0];
        $this->adminUser->assignRole('admin');
        $this->categoryRepo = \App::make(CategoryRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateCategory()
    {
        $this->be($this->adminUser);
        $category = factory(Category::class)->make(['user_id'=>$this->adminUser->id])->toArray();

        $createdCategory = $this->categoryRepo->create($category);

        $createdCategory = $createdCategory->toArray();
        $this->assertArrayHasKey('id', $createdCategory);
        $this->assertNotNull($createdCategory['id'], 'Created Category must have id specified');
        $this->assertNotNull(Category::find($createdCategory['id']), 'Category with given id must be in DB');
        $this->assertModelData($category, $createdCategory);
    }

    /**
     * @test read
     */
    public function testReadCategory()
    {
        $this->be($this->adminUser);
        $category = factory(Category::class)->create();

        $dbCategory = $this->categoryRepo->find($category->id);

        $dbCategory = $dbCategory->toArray();
        $this->assertModelData($category->toArray(), $dbCategory);
    }

    /**
     * @test update
     */
    public function testUpdateCategory()
    {
        $this->be($this->adminUser);
        $category = factory(Category::class)->create();
        $fakeCategory = factory(Category::class)->make(['user_id'=>$this->adminUser->id])->toArray();

        $updatedCategory = $this->categoryRepo->update($fakeCategory, $category->id);

        $this->assertModelData($fakeCategory, $updatedCategory->toArray());
        $dbCategory = $this->categoryRepo->find($category->id);
        $this->assertModelData($fakeCategory, $dbCategory->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteCategory()
    {
        $this->be($this->adminUser);
        $category = factory(Category::class)->create();

        $resp = $this->categoryRepo->delete($category->id);

        $this->assertTrue($resp);
        $this->assertNull(Category::find($category->id), 'Category should not exist in DB');
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
