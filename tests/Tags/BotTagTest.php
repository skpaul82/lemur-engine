<?php namespace Tests\Tags;

use App\Models\BotProperty;
use App\Models\Conversation;
use App\Tags\BotTag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TagTestCase;
use Tests\TestCase;
use UnitTestCategoriesTableSeeder;

class BotTagTest extends TagTestCase
{
    protected $parser;
    protected $mock;
    protected $conversation;
    protected $botProperties;
    protected $botTag;

    public function setUp() :void
    {

        parent::setUp();

        $this->botProperties = Mockery::mock(BotProperty::class);

        $this->botProperties->shouldReceive('getAttribute')
            ->withArgs(['value'])->andReturn('23');

        $this->conversation->shouldReceive('getAttribute')
            ->withArgs(['botProperty'])->andReturn($this->botProperties);
        $this->conversation->shouldReceive('getAttribute')
            ->withArgs(['id'])->andReturn(1);

        $this->conversation->shouldReceive('getBotProperty')
            ->withArgs(['age'])->andReturn($this->botProperties);

        $this->botTag = new BotTag($this->conversation, ['NAME'=>'age']);
    }


    /**
     * @return void
     */
    public function testGetTagName()
    {
        $this->assertEquals('Bot', $this->botTag->getTagName());
    }

    /**
     * test Bot::constructor()
     *
     * @return void
     */
    public function testConstructor()
    {
        $this->assertTrue($this->botTag->hasAttributes());
        $this->assertTrue($this->botTag->hasAttribute('NAME'));
        $this->assertEquals('age', $this->botTag->getAttribute('NAME'));
    }

    /**
     * test Bot::closeTag()
     *
     * @return void
     */
    public function testCloseTag()
    {
        $this->botTag->closeTag();
        $this->assertEquals('23', $this->botTag->getTagContents()[0]);
    }

    /**
     * test Bot::closeTag()
     *
     * @return void
     */
    public function testCloseTagWithNoAttributes()
    {
        $botTag = new BotTag($this->conversation, []);
        $botTag->closeTag();
        $this->assertEmpty($botTag->getTagContents());
    }


    /**
     *
     */
    public function tearDown() :void
    {
        parent::tearDown();
    }
}
