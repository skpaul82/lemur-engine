<?php namespace Tests\Tags;

use App\Models\Bot;
use App\Models\Conversation;
use App\Models\Turn;
use App\Models\Category;
use App\Tags\StarTag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TagTestCase;
use Tests\TestCase;

class StarTagTest extends TagTestCase
{
    protected $parser;
    protected $mock;
    protected $conversation;
    protected $turn;
    protected $tag;

    /**
     * todo liz add more tests
     */
    public function setUp() :void
    {

        //some common parts of the conversation
        //are set up in the parent TagTestCase constructor
        parent::setUp();

        $this->conversation->shouldReceive('debug');

        $this->tag = new StarTag($this->conversation, []);
    }


    /**
     * @return void
     */
    public function testGetTagName()
    {
        $this->assertEquals('Star', $this->tag->getTagName());
    }


    /**
     *
     */
    public function tearDown() :void
    {
        parent::tearDown();
    }
}
