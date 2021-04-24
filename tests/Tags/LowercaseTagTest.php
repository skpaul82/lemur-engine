<?php namespace Tests\Tags;

use App\Models\Conversation;
use App\Models\Turn;
use App\Tags\LowercaseTag;
use Mockery;
use Tests\TagTestCase;
use Tests\TestCase;

class LowercaseTagTest extends TagTestCase
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

        $this->tag = new LowercaseTag($this->conversation, []);
    }


    /**
     * @return void
     */
    public function testGetTagName()
    {
        $this->assertEquals('Lowercase', $this->tag->getTagName());
    }



    /**
     * test Bot::closeTag()
     *
     * @return void
     */
    public function testCloseTag()
    {
        $this->tag->buildResponse('MAKE LOWERCASE');
        $this->tag->closeTag();
        $this->assertEquals('make lowercase', $this->tag->getCurrentResponse());
    }


    /**
     *
     */
    public function tearDown() :void
    {
        parent::tearDown();
    }
}
