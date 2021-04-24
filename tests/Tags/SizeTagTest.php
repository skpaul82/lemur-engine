<?php namespace Tests\Tags;

use App\Models\Bot;
use App\Models\Conversation;
use App\Models\Turn;
use App\Models\Category;
use App\Tags\SizeTag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TagTestCase;
use Tests\TestCase;

class SizeTagTest extends TagTestCase
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

        $bot = Mockery::mock(Bot::class);

        $bot->shouldReceive('getAttribute')
            ->withArgs(['id'])->andReturn(1);

        $this->conversation->shouldReceive('getAttribute')
            ->withArgs(['bot'])->andReturn($bot);


        $this->tag = new SizeTag($this->conversation, []);
    }


    /**
     * @return void
     */
    public function testGetTagName()
    {
        $this->assertEquals('Size', $this->tag->getTagName());
    }



    /**
     * test Bot::closeTag()
     *
     * @return void
     */
    public function testCloseTag()
    {

        $this->tag->closeTag();

        $total = Category::all()->count();

        $this->assertEquals($total, $this->tag->getCurrentResponse());
    }


    /**
     *
     */
    public function tearDown() :void
    {
        parent::tearDown();
    }
}
