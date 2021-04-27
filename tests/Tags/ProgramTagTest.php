<?php namespace Tests\Tags;

use App\Models\Conversation;
use App\Models\Turn;
use App\Services\TalkService;
use App\Tags\ProgramTag;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TagTestCase;
use Tests\TestCase;
use UnitTestCategoriesTableSeeder;

class ProgramTagTest extends TagTestCase
{
    protected $parser;
    protected $mock;
    protected $conversation;
    protected $turn;
    protected $tag;

    public function setUp() :void
    {

        //some common parts of the conversation
        //are set up in the parent TagTestCase constructor
        parent::setUp();

        $this->conversation->shouldReceive('debug');

        $this->conversation->shouldReceive('getAttribute')
            ->withArgs(['bot_id'])->andReturn(1);

        $this->conversation->shouldReceive('getAttribute')
            ->withArgs(['client_id'])->andReturn(1);

        $this->tag = new ProgramTag($this->conversation, []);
    }


    /**
     * @return void
     */
    public function testGetTagName()
    {
        $this->assertEquals('Program', $this->tag->getTagName());
    }



    /**
     * test Bot::closeTag()
     *
     * @return void
     */
    public function testCloseTag()
    {
        $this->tag->closeTag();
        $this->assertEquals(config('lemur_version.bot.id'), $this->tag->getCurrentTagContents());
    }

    /**
     *
     */
    public function tearDown() :void
    {
        parent::tearDown();
    }
}
