<?php namespace Tests\Repositories;

use App\Models\User;
use App\Models\WordTransformation;
use App\Repositories\WordTransformationRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class WordTransformationRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var WordTransformationRepository
     */
    protected $wordTransformationRepo;
    protected $adminUser;

    public function setUp() : void
    {
        parent::setUp();
        //create an admin user.....
        $adminUser = factory(User::class, 1)->create();
        $this->adminUser = $adminUser[0];
        $this->adminUser->assignRole('admin');
        $this->wordTransformationRepo = \App::make(WordTransformationRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateWordTransformation()
    {
        $this->be($this->adminUser);
        $wordTransformation = factory(WordTransformation::class)
            ->make(['user_id'=>$this->adminUser->id])->toArray();

        $createdWordTransformation = $this->wordTransformationRepo->create($wordTransformation);

        $createdWordTransformation = $createdWordTransformation->toArray();
        $this->assertArrayHasKey('id', $createdWordTransformation);
        $this->assertNotNull(
            $createdWordTransformation['id'],
            'Created WordTransformation must have id specified'
        );
        $this->assertNotNull(
            WordTransformation::find($createdWordTransformation['id']),
            'WordTransformation with given id must be in DB'
        );
        $this->assertModelData($wordTransformation, $createdWordTransformation);
    }

    /**
     * @test read
     */
    public function testReadWordTransformation()
    {
        $this->be($this->adminUser);
        $wordTransformation = factory(WordTransformation::class)->create();

        $dbWordTransformation = $this->wordTransformationRepo->find($wordTransformation->id);

        $dbWordTransformation = $dbWordTransformation->toArray();
        $this->assertModelData($wordTransformation->toArray(), $dbWordTransformation);
    }

    /**
     * @test update
     */
    public function testUpdateWordTransformation()
    {
        $this->be($this->adminUser);
        $wordTransformation = factory(WordTransformation::class)->create();
        $fakeWordTransformation = factory(WordTransformation::class)
            ->make(['user_id'=>$this->adminUser->id])->toArray();

        $updatedWordTransformation = $this->wordTransformationRepo
            ->update($fakeWordTransformation, $wordTransformation->id);

        $this->assertModelData($fakeWordTransformation, $updatedWordTransformation->toArray());
        $dbWordTransformation = $this->wordTransformationRepo->find($wordTransformation->id);
        $this->assertModelData($fakeWordTransformation, $dbWordTransformation->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteWordTransformation()
    {
        $this->be($this->adminUser);
        $wordTransformation = factory(WordTransformation::class)->create();

        $resp = $this->wordTransformationRepo->delete($wordTransformation->id);

        $this->assertTrue($resp);
        $this->assertNull(
            WordTransformation::find($wordTransformation->id),
            'WordTransformation should not exist in DB'
        );
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
