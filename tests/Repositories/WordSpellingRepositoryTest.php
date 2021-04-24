<?php namespace Tests\Repositories;

use App\Models\User;
use App\Models\WordSpelling;
use App\Repositories\WordSpellingRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class WordSpellingRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var WordSpellingRepository
     */
    protected $wordSpellingRepo;
    protected $adminUser;

    public function setUp() : void
    {
        parent::setUp();
        //create an admin user.....
        $adminUser = factory(User::class, 1)->create();
        $this->adminUser = $adminUser[0];
        $this->adminUser->assignRole('admin');
        $this->wordSpellingRepo = \App::make(WordSpellingRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateWordSpelling()
    {
        $this->be($this->adminUser);
        $wordSpelling = factory(WordSpelling::class)
            ->make(['user_id'=>$this->adminUser->id])->toArray();

        $createdWordSpelling = $this->wordSpellingRepo->create($wordSpelling);

        $createdWordSpelling = $createdWordSpelling->toArray();
        $this->assertArrayHasKey('id', $createdWordSpelling);
        $this->assertNotNull(
            $createdWordSpelling['id'],
            'Created WordSpelling must have id specified'
        );
        $this->assertNotNull(
            WordSpelling::find($createdWordSpelling['id']),
            'WordSpelling with given id must be in DB'
        );
        $this->assertModelData($wordSpelling, $createdWordSpelling);
    }

    /**
     * @test read
     */
    public function testReadWordSpelling()
    {
        $this->be($this->adminUser);
        $wordSpelling = factory(WordSpelling::class)->create();

        $dbWordSpelling = $this->wordSpellingRepo->find($wordSpelling->id);

        $dbWordSpelling = $dbWordSpelling->toArray();
        $this->assertModelData($wordSpelling->toArray(), $dbWordSpelling);
    }

    /**
     * @test update
     */
    public function testUpdateWordSpelling()
    {
        $this->be($this->adminUser);
        $wordSpelling = factory(WordSpelling::class)->create();
        $fakeWordSpelling = factory(WordSpelling::class)
            ->make(['user_id'=>$this->adminUser->id])->toArray();

        $updatedWordSpelling = $this->wordSpellingRepo->update($fakeWordSpelling, $wordSpelling->id);

        $this->assertModelData($fakeWordSpelling, $updatedWordSpelling->toArray());
        $dbWordSpelling = $this->wordSpellingRepo->find($wordSpelling->id);
        $this->assertModelData($fakeWordSpelling, $dbWordSpelling->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteWordSpelling()
    {
        $this->be($this->adminUser);
        $wordSpelling = factory(WordSpelling::class)->create();

        $resp = $this->wordSpellingRepo->delete($wordSpelling->id);

        $this->assertTrue($resp);
        $this->assertNull(
            WordSpelling::find($wordSpelling->id),
            'WordSpelling should not exist in DB'
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
