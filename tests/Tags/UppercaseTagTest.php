<?php namespace Tests\Tags;

use App\Models\Conversation;
use App\Models\Turn;
use App\Tags\UppercaseTag;
use Mockery;
use Tests\TagTestCase;
use Tests\TestCase;

class UppercaseTagTest extends TagTestCase
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

        $this->tag = new UppercaseTag($this->conversation, []);
    }


    /**
     * @return void
     */
    public function testGetTagName()
    {
        $this->assertEquals('Uppercase', $this->tag->getTagName());
    }



    /**
     * test Bot::closeTag()
     *
     * @return void
     */
    public function testCloseTag()
    {
        $this->tag->buildResponse('make uppercase');
        $this->tag->closeTag();
        $this->assertEquals('MAKE UPPERCASE', $this->tag->getCurrentResponse());
    }


    /**
     *
     */
    public function tearDown() :void
    {
        parent::tearDown();
    }
}
