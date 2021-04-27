<?php namespace Tests\Tags;

use App\Models\Bot;
use App\Models\Conversation;
use App\Models\Turn;
use App\Models\Category;
use App\Tags\IdTag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TagTestCase;
use Tests\TestCase;

class IdTagTest extends TagTestCase
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

        $bot = Mockery::mock(Bot::class);

        $bot->shouldReceive('getAttribute')
            ->withArgs(['id'])->andReturn(1);

        $this->conversation->shouldReceive('getAttribute')
            ->withArgs(['bot'])->andReturn($bot);

        $this->conversation->shouldReceive('getAttribute')
            ->withArgs(['slug'])->andReturn('test-123');

        $this->conversation->shouldReceive('debug');

        $this->tag = new IdTag($this->conversation, []);
    }


    /**
     * @return void
     */
    public function testGetTagName()
    {
        $this->assertEquals('Id', $this->tag->getTagName());
    }



    /**
     * test Bot::closeTag()
     *
     * @return void
     */
    public function testCloseTag()
    {
        $this->tag->closeTag();
        $this->assertEquals('test-123', $this->tag->getCurrentTagContents());
    }



    /**
     *
     */
    public function tearDown() :void
    {
        parent::tearDown();
    }
}
