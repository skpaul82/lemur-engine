<?php namespace Tests\Tags;

use App\Models\Conversation;
use App\Models\Turn;
use App\Tags\FormalTag;
use Mockery;
use Tests\TagTestCase;
use Tests\TestCase;

class FormalTagTest extends TagTestCase
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

        $this->conversation->shouldReceive('getAttribute')
            ->withArgs(['bot_id'])->andReturn(1);

        $this->conversation->shouldReceive('getAttribute')
            ->withArgs(['client_id'])->andReturn(1);

        $this->conversation->shouldReceive('debug');

        $this->tag = new FormalTag($this->conversation, []);
    }


    /**
     * @return void
     */
    public function testGetTagName()
    {
        $this->assertEquals('Formal', $this->tag->getTagName());
    }



    /**
     * test Bot::closeTag()
     *
     * @return void
     */
    public function testCloseTag()
    {
        $this->tag->buildResponse('MAKE FORMAL');
        $this->tag->closeTag();
        $this->assertEquals('Make Formal', $this->tag->getCurrentResponse());
    }



    /**
     *
     */
    public function tearDown() :void
    {
        parent::tearDown();
    }
}
