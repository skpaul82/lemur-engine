<?php namespace Tests\Tags;

use App\Models\BotProperty;
use App\Models\Conversation;
use App\Tags\CategoryTag;
use App\Tags\DateTag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TagTestCase;
use Tests\TestCase;
use UnitTestCategoriesTableSeeder;

class DateTagTest extends TagTestCase
{
    protected $parser;
    protected $mock;
    protected $conversation;
    protected $botProperties;
    protected $dateTag;

    public function setUp() :void
    {

        parent::setUp();
    }


    /**
     * test Bot::constructor()
     *
     * @return void
     */
    public function testConstructorWithAttributes()
    {
        $dateTag = new DateTag($this->conversation, ['LOCALE'=>'en','FORMAT'=>'%x','TIMEZONE'=>'+1']);
        $this->assertTrue($dateTag->hasAttributes());
        $this->assertTrue($dateTag->hasAttribute('LOCALE'));
        $this->assertTrue($dateTag->hasAttribute('FORMAT'));
        $this->assertTrue($dateTag->hasAttribute('TIMEZONE'));
        $this->assertEquals('en', $dateTag->getAttribute('LOCALE'));
        $this->assertEquals('%x', $dateTag->getAttribute('FORMAT'));
        $this->assertEquals('+1', $dateTag->getAttribute('TIMEZONE'));
    }



    /**
     * test Bot::constructor()
     *
     * @return void
     */
    public function testConstructorSetsDefaultValues()
    {
        $dateTag = new DateTag($this->conversation, []);
        $this->assertTrue($dateTag->hasAttributes());
        $this->assertTrue($dateTag->hasAttribute('LOCALE'));
        $this->assertTrue($dateTag->hasAttribute('FORMAT'));
        $this->assertTrue($dateTag->hasAttribute('TIMEZONE'));
        $this->assertEquals('en_US', $dateTag->getAttribute('LOCALE'));
        $this->assertEquals('%B %d %Y', $dateTag->getAttribute('FORMAT'));
        $this->assertEquals('', $dateTag->getAttribute('TIMEZONE'));
    }

    /**
     * test Bot::closeTag()
     *
     * @return void
     */
    public function testCloseTag()
    {
        $dateTag = new DateTag($this->conversation, ['LOCALE'=>'en','FORMAT'=>'%x','TIMEZONE'=>'']);
        $dateTag->closeTag();
        $date = date('m/d/y');
        $this->assertEquals($date, $dateTag->getTagContents()[0]);
    }

    /**
     * test Bot::closeTag()
     *
     * @return void
     */
    public function testCloseTagWithNoAttributes()
    {
        $dateTag = new DateTag($this->conversation, []);
        $dateTag->closeTag();
        $date = date('F d Y');
        $this->assertEquals($date, $dateTag->getTagContents()[0]);
    }

    /**
     * @return void
     */
    public function testGetTagName()
    {
        $dateTag = new DateTag($this->conversation, []);
        $this->assertEquals('Date', $dateTag->getTagName());
    }

    /**
     *
     */
    public function tearDown() :void
    {
        parent::tearDown();
    }
}
