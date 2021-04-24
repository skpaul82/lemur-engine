<?php namespace Tests\Tags;

use App\Models\Conversation;
use App\Models\Turn;
use App\Tags\Html;
use App\Tags\HtmlTag;
use Mockery;
use Tests\TagTestCase;
use Tests\TestCase;

class HtmlTagTest extends TagTestCase
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
    }


    /**
     * @return void
     */
    public function testGetTagName()
    {
        $this->conversation->shouldReceive('getAllowHtml')->andReturn(true);
        $this->tag = new HtmlTag($this->conversation, []);
        $this->tag->setTagName('br');
        $this->tag->setTagType('single');
        $this->assertEquals('br', $this->tag->getTagName());
    }



    /**
     * this is not a unit test its more of a integration test... my brain cant work out a way to test this
     */
    public function testCloseTagSingleWithHtmlOn()
    {

        $this->conversation->shouldReceive('getAllowHtml')->andReturn(true);
        $this->tag = new HtmlTag($this->conversation, []);
        $this->tag->setTagName('br');
        $this->tag->setTagType('single');
        $this->tag->processContents('hello');
        $this->assertEquals("hello<br/>", $this->tag->getCurrentResponse());
    }


    /**
     * this is not a unit test its more of a integration test... my brain cant work out a way to test this
     */
    public function testCloseTagSingleHtmlOff()
    {

        $this->conversation->shouldReceive('getAllowHtml')->andReturn(false);
        $this->tag = new HtmlTag($this->conversation, []);
        $this->tag->setTagName('br');
        $this->tag->setTagType('single');
        $this->tag->processContents('hello');
        $this->assertEquals("hello", $this->tag->getCurrentResponse());
    }


    /**
     * this is not a unit test its more of a integration test... my brain cant work out a way to test this
     */
    public function testCloseTagWrappedHtmlOn()
    {

        $this->conversation->shouldReceive('getAllowHtml')->andReturn(true);
        $this->tag = new HtmlTag($this->conversation, []);
        $this->tag->setTagName('strong');
        $this->tag->setTagType('wrapped');
        $this->tag->processContents('hello');
        $this->assertEquals("<strong>hello</strong>", $this->tag->getCurrentResponse());
    }


    /**
     * this is not a unit test its more of a integration test... my brain cant work out a way to test this
     */
    public function testCloseTagWrappedHtmlOff()
    {

        $this->conversation->shouldReceive('getAllowHtml')->andReturn(false);
        $this->tag = new HtmlTag($this->conversation, []);
        $this->tag->setTagName('strong');
        $this->tag->setTagType('wrapped');
        $this->tag->processContents('hello');
        $this->assertEquals("hello", $this->tag->getCurrentResponse());
    }

    /**
     *
     */
    public function tearDown() :void
    {
        parent::tearDown();
    }
}
