<?php namespace Tests\Repositories;

use App\Models\User;
use App\Models\WordSpellingGroup;
use App\Repositories\WordSpellingGroupRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class WordSpellingGroupRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var WordSpellingGroupRepository
     */
    protected $wordSpellingGroupRepo;
    protected $adminUser;

    public function setUp() : void
    {
        parent::setUp();
        //create an admin user.....
        $adminUser = factory(User::class, 1)->create();
        $this->adminUser = $adminUser[0];
        $this->adminUser->assignRole('admin');
        $this->wordSpellingGroupRepo = \App::make(WordSpellingGroupRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateWordSpellingGroup()
    {
        $this->be($this->adminUser);
        $wordSpellingGroup = factory(WordSpellingGroup::class)
            ->make(['user_id'=>$this->adminUser->id])->toArray();

        $createdWordSpellingGroup = $this->wordSpellingGroupRepo->create($wordSpellingGroup);

        $createdWordSpellingGroup = $createdWordSpellingGroup->toArray();
        $this->assertArrayHasKey('id', $createdWordSpellingGroup);
        $this->assertNotNull(
            $createdWordSpellingGroup['id'],
            'Created WordSpellingGroup must have id specified'
        );
        $this->assertNotNull(
            WordSpellingGroup::find($createdWordSpellingGroup['id']),
            'WordSpellingGroup with given id must be in DB'
        );
        $this->assertModelData($wordSpellingGroup, $createdWordSpellingGroup);
    }

    /**
     * @test read
     */
    public function testReadWordSpellingGroup()
    {
        $this->be($this->adminUser);
        $wordSpellingGroup = factory(WordSpellingGroup::class)->create();

        $dbWordSpellingGroup = $this->wordSpellingGroupRepo->find($wordSpellingGroup->id);

        $dbWordSpellingGroup = $dbWordSpellingGroup->toArray();
        $this->assertModelData($wordSpellingGroup->toArray(), $dbWordSpellingGroup);
    }

    /**
     * @test update
     */
    public function testUpdateWordSpellingGroup()
    {
        $this->be($this->adminUser);
        $wordSpellingGroup = factory(WordSpellingGroup::class)->create();
        $fakeWordSpellingGroup = factory(WordSpellingGroup::class)
            ->make(['user_id'=>$this->adminUser->id])->toArray();

        $updatedWordSpellingGroup = $this->wordSpellingGroupRepo
            ->update($fakeWordSpellingGroup, $wordSpellingGroup->id);

        $this->assertModelData($fakeWordSpellingGroup, $updatedWordSpellingGroup->toArray());
        $dbWordSpellingGroup = $this->wordSpellingGroupRepo->find($wordSpellingGroup->id);
        $this->assertModelData($fakeWordSpellingGroup, $dbWordSpellingGroup->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteWordSpellingGroup()
    {
        $this->be($this->adminUser);
        $wordSpellingGroup = factory(WordSpellingGroup::class)->create();

        $resp = $this->wordSpellingGroupRepo->delete($wordSpellingGroup->id);

        $this->assertTrue($resp);
        $this->assertNull(
            WordSpellingGroup::find($wordSpellingGroup->id),
            'WordSpellingGroup should not exist in DB'
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
