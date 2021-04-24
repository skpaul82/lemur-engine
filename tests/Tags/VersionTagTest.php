<?php namespace Tests\Tags;

use App\Models\BotProperty;
use App\Models\Conversation;
use App\Models\Turn;
use App\Services\TalkService;
use App\Tags\Bot;
use App\Tags\Srai;
use App\Tags\VersionTag;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TagTestCase;
use Tests\TestCase;
use UnitTestCategoriesTableSeeder;

class VersionTagTest extends TagTestCase
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

        $this->tag = new VersionTag($this->conversation, []);
    }


    /**
     * @return void
     */
    public function testGetTagName()
    {
        $this->assertEquals('Version', $this->tag->getTagName());
    }



    /**
     * test Bot::closeTag()
     *
     * @return void
     */
    public function testCloseTag()
    {
        $this->tag->closeTag();
        $this->assertEquals(config('lemur_version.bot.id'), $this->tag->getCurrentResponse());
    }


    /**
     *
     */
    public function tearDown() :void
    {
        parent::tearDown();
    }
}
