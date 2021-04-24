<?php namespace Tests\Tags;

use App\Models\BotProperty;
use App\Models\Conversation;
use App\Tags\CategoryTag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Swagger\Annotations\Tag;
use Tests\TagTestCase;
use Tests\TestCase;

class CategoryTagTest extends TagTestCase
{
    protected $parser;
    protected $mock;
    protected $conversation;
    protected $tag;

    public function setUp() :void
    {

        parent::setUp();

        $this->tag = new CategoryTag($this->conversation);
    }


    /**
     * @return void
     */
    public function testGetTagName()
    {
        $this->assertEquals('Category', $this->tag->getTagName());
    }

    /**
     * test Bot::constructor()
     *
     * @return void
     */
    public function testConstructor()
    {
        $this->assertEmpty($this->tag->hasAttributes());
    }



    /**
     *
     */
    public function tearDown() :void
    {
        parent::tearDown();
    }
}
