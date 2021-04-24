<?php namespace Tests\Repositories;

use App\Models\Normalization;
use App\Models\User;
use App\Repositories\NormalizationRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class NormalizationRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var NormalizationRepository
     */
    protected $normalizationRepo;
    protected $adminUser;

    public function setUp() : void
    {
        parent::setUp();
        //create an admin user.....
        $adminUser = factory(User::class, 1)->create();
        $this->adminUser = $adminUser[0];
        $this->adminUser->assignRole('admin');
        $this->normalizationRepo = \App::make(NormalizationRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateNormalization()
    {
        $normalization = factory(Normalization::class)->make()->toArray();

        $createdNormalization = $this->normalizationRepo->create($normalization);

        $createdNormalization = $createdNormalization->toArray();
        $this->assertArrayHasKey('id', $createdNormalization);
        $this->assertNotNull(
            $createdNormalization['id'],
            'Created Normalization must have id specified'
        );
        $this->assertNotNull(
            Normalization::find($createdNormalization['id']),
            'Normalization with given id must be in DB'
        );
        $this->assertModelData($normalization, $createdNormalization);
    }

    /**
     * @test read
     */
    public function testReadNormalization()
    {
        $normalization = factory(Normalization::class)->create();

        $dbNormalization = $this->normalizationRepo->find($normalization->id);

        $dbNormalization = $dbNormalization->toArray();
        $this->assertModelData($normalization->toArray(), $dbNormalization);
    }

    /**
     * @test update
     */
    public function testUpdateNormalization()
    {
        $normalization = factory(Normalization::class)->create();
        $fakeNormalization = factory(Normalization::class)->make()->toArray();

        $updatedNormalization = $this->normalizationRepo->update($fakeNormalization, $normalization->id);

        $this->assertModelData($fakeNormalization, $updatedNormalization->toArray());
        $dbNormalization = $this->normalizationRepo->find($normalization->id);
        $this->assertModelData($fakeNormalization, $dbNormalization->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteNormalization()
    {
        $normalization = factory(Normalization::class)->create();

        $resp = $this->normalizationRepo->delete($normalization->id);

        $this->assertTrue($resp);
        $this->assertNull(
            Normalization::find($normalization->id),
            'Normalization should not exist in DB'
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
