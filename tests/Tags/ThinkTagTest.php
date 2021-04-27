<?php namespace Tests\Tags;

use App\Models\Bot;
use App\Models\Conversation;
use App\Models\Turn;
use App\Models\Category;
use App\Tags\ThinkTag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TagTestCase;
use Tests\TestCase;

class ThinkTagTest extends TagTestCase
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

        $this->tag = new ThinkTag($this->conversation, []);
    }


    /**
     * @return void
     */
    public function testGetTagName()
    {
        $this->assertEquals('Think', $this->tag->getTagName());
    }



    /**
     * test Bot::closeTag()
     *
     * @return void
     */
    public function testBuildResponseFromThink()
    {

        $this->tag->closeTag();
        $this->assertEmpty($this->tag->getCurrentTagContents());
    }



    /**
     *
     */
    public function tearDown() :void
    {
        parent::tearDown();
    }
}
