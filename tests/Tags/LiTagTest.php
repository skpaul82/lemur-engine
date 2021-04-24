<?php namespace Tests\Tags;

use App\Models\Conversation;
use App\Models\Turn;
use App\Tags\LiTag;
use Mockery;
use Tests\TagTestCase;
use Tests\TestCase;

class LiTagTest extends TagTestCase
{
    protected $parser;
    protected $mock;
    protected $conversation;
    protected $turn;
    protected $tag;

    /**
     * todo liz complete the tests
     */
    public function setUp() :void
    {

        //some common parts of the conversation
        //are set up in the parent TagTestCase constructor
        parent::setUp();

        $this->conversation->shouldReceive('getAttribute')
            ->withArgs(['bot_id'])->andReturn(1);

        $this->conversation->shouldReceive('getAttribute')
            ->withArgs(['client_id'])->andReturn(1);

        $this->conversation->shouldReceive('debug');

        $this->tag = new LiTag($this->conversation, []);
    }


    /**
     * @return void
     */
    public function testGetTagName()
    {
        $this->assertEquals('Li', $this->tag->getTagName());
    }



    /**
     *
     */
    public function tearDown() :void
    {
        parent::tearDown();
    }
}
