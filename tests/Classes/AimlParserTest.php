<?php namespace Tests\Classes;

use App\Models\Category;
use App\Models\Conversation;
use App\Models\Turn;
use App\Classes\AimlParser;
use App\Tags\Condition;
use App\Tags\Li;
use App\Tags\Template;
use Illuminate\Support\Facades\Log;
use Mockery;
use Tests\TestCase;

class AimlParserTest extends TestCase
{
    protected $aimlParser;
    protected $mock;
    protected $conversation;
    protected $turn;
    protected $category;
    protected $botTag;

    public function setUp() :void
    {

        parent::setup();

        $this->turn = Mockery::mock(Turn::class);
        $this->turn->shouldReceive('debug');
        $this->turn->shouldReceive('getAttribute');

        $this->conversation = Mockery::mock(Conversation::class);
        $this->conversation->shouldReceive('getAttribute')
            ->withArgs(['id'])->andReturn(1);
        $this->conversation->shouldReceive('currentTurnId')
            ->andReturn(1);
        $this->conversation->shouldReceive('debug');
        $this->conversation->shouldReceive('getAttribute')->withArgs(['currentConversationTurn'])
            ->andReturn($this->turn);


        $this->category = Mockery::mock(Category::class);


        $this->aimlParser = new AimlParser();
        $this->aimlParser->setConversation($this->conversation);
        $this->aimlParser->setCategory($this->category);
    }


    /**
     *
     */
    public function testParseWithBlankTemplate()
    {
        $this->assertEmpty($this->aimlParser->parse("<template></template>"));
    }

    /**
     *getAllowHtml
     */
    public function testParseWithBrokenAimlAndExpectException()
    {

        $this->expectException("ErrorException");
        $this->aimlParser->parse("<template><template>");
    }

    /**
     *
     */
    public function testParseWithValidTemplateTag()
    {
        $check = "<template>This is a test response</template>";
        $this->assertEquals("This is a test response", $this->aimlParser->parse($check));
    }



    /**
     *
     */
    public function testSetXmlParser()
    {
        $this->aimlParser->setXmlParser('UTF-8');
        $xmlParser = $this->aimlParser->getXmlParser();

        $this->assertNotFalse($xmlParser);
        $this->assertIsResource($xmlParser);
    }

    /**
     *
     */
    public function testGetXmlParser()
    {

        $xmlParser = $this->aimlParser->getXmlParser();
        $this->assertEmpty($xmlParser);

        $this->aimlParser->setXmlParser('UTF-8');
        $xmlParser = $this->aimlParser->getXmlParser();
        $this->assertNotFalse($xmlParser);
    }



    /**
     *
     */
    public function testSetConditionStackWithNoConditions()
    {
        $template = '<template>This is a test response</template>';
        $this->aimlParser->setConditionStack($template);
        $conditionStack = $this->aimlParser->getConditionStack();
        $this->assertEmpty($conditionStack);
    }

    /**
     *
     */
    public function testSetConditionStackWith1Condition()
    {

        $conditionStack = $this->aimlParser->getConditionStack();
        $this->assertEmpty($conditionStack);

        $conditionA = '<condition name="state" value="sad">I am sad!</condition>';
        $templateA = '<template>'.$conditionA.'</template>';
        $this->aimlParser->setConditionStack($templateA);

        $conditionStack = $this->aimlParser->getConditionStack();

        $this->assertEquals(1, count($conditionStack));
        $this->assertNotEmpty($conditionStack);
        $this->assertEquals($conditionA, $conditionStack[0]);
    }

    /**
     *
     */
    public function testSetConditionStackWith2Conditions()
    {

        $conditionStack = $this->aimlParser->getConditionStack();
        $this->assertEmpty($conditionStack);

        $conditionA = '<condition name="state" value="foo">I am foo!</condition>';
        $conditionB = '<condition name="state" value="bar">I am bar!</condition>';
        $templateA = '<template>'.$conditionA.$conditionB.'</template>';
        $this->aimlParser->setConditionStack($templateA);

        $conditionStack = $this->aimlParser->getConditionStack();
        $this->assertNotEmpty($conditionStack);

        $this->assertEquals(2, count($conditionStack));
        $this->assertEquals($conditionA, $conditionStack[0]);
        $this->assertEquals($conditionB, $conditionStack[1]);
    }

    /**
     *
     */
    public function testGetConditionStack()
    {

        $conditionStack = $this->aimlParser->getConditionStack();
        $this->assertEmpty($conditionStack);

        $conditionA = '<condition name="state" value="sad">I am sad!</condition>';
        $templateA = '<template>'.$conditionA.'</template>';
        $this->aimlParser->setConditionStack($templateA);
        $conditionStack = $this->aimlParser->getConditionStack();
        $this->assertNotEmpty($conditionStack);
    }

    /**
     * this is not a unit test its more of a integration test... my brain cant work out a way to test this
     */
    public function testStartElementWithHtmlOn()
    {
        $this->conversation->shouldReceive('getAllowHtml')->andReturn(true);
        $check = "<template><strong>Hi</strong></template>";
        $this->assertEquals("<strong>Hi</strong>", $this->aimlParser->parse($check));
    }

    /**
     * this is not a unit test its more of a integration test... my brain cant work out a way to test this
     */
    public function testStartElementWithHtmlOff()
    {
        $this->conversation->shouldReceive('getAllowHtml')->andReturn(false);
        $this->assertEquals("Hi", $this->aimlParser->parse("<template><strong>Hi</strong></template>"));
    }

    /**
     * this is not a unit test its more of a integration test... my brain cant work out a way to test this
     */
    public function testCdataWithHtmlOn()
    {

        $this->conversation->shouldReceive('getAllowHtml')->andReturn(true);
        $check = "<template><![CDATA[<html><b>Test A </b> PART B</html>]]></template>";
        $this->assertEquals("<html><b>Test A </b> PART B</html>", $this->aimlParser->parse($check));
    }


    /**
     * this is not a unit test its more of a integration test... my brain cant work out a way to test this
     */
    public function testCdataWithHtmlOff()
    {

        $this->conversation->shouldReceive('getAllowHtml')->andReturn(false);
        $check = "<template><![CDATA[<html><b>Test A </b> PART B</html>]]></template>";
        $this->assertEquals("<html><b>Test A </b> PART B</html>", $this->aimlParser->parse($check));
    }

    /**
     * this is not a unit test its more of a integration test... my brain cant work out a way to test this
     */
    public function testEndElementWithHtmlOn()
    {
        $this->conversation->shouldReceive('getAllowHtml')->andReturn(true);
        $this->assertEquals(
            "<strong>Hi</strong>",
            $this->aimlParser->parse("<template><strong>Hi</strong></template>")
        );
    }

    /**
     * this is not a unit test its more of a integration test... my brain cant work out a way to test this
     */
    public function testEndElementWithHtmlOff()
    {
        $this->conversation->shouldReceive('getAllowHtml')->andReturn(false);
        $this->assertEquals(
            "Hi",
            $this->aimlParser->parse("<template><strong>Hi</strong></template>")
        );
    }

    /**
     *
     */
    public function testCleanTagClassName()
    {

        $cleanName = $this->aimlParser->cleanTagClassName('xxXxxxxx');
        $this->assertEquals('Xxxxxxxx', $cleanName);

        $cleanName = $this->aimlParser->cleanTagClassName('xxXxxxxx');
        $this->assertEquals('Xxxxxxxx', $cleanName);

        $cleanName = $this->aimlParser->cleanTagClassName('xxX xxx');
        $this->assertEquals('XxxXxx', $cleanName);

        $cleanName = $this->aimlParser->cleanTagClassName('xx_xxx');
        $this->assertEquals('XxXxx', $cleanName);
    }




    /**
     *
     */
    public function testCheckSetParentTagIsInvalid()
    {


        //add some tags to the global tag stack
        $this->fillGlobalTagStack();
        //this should be true
        $tag = $this->aimlParser->getTagStack()->getItemCurrentPosition(2);
        $this->assertTrue($tag->getIsTagValid());

        //now lets update the value of valid for item 1
        $tag = $this->aimlParser->getTagStack()->getItemCurrentPosition(1);
        $this->assertTrue($tag->getIsTagValid());
        $tag->setIsTagValid(false);
        $position = $this->aimlParser->getTagStack()->getPositionOfTag($tag->getTagId());
        $this->aimlParser->getTagStack()->overWrite($tag, $position);
        $this->assertFalse($tag->getIsTagValid());

        //now lets recheck item 2
        $tag = $this->aimlParser->getTagStack()->getItemCurrentPosition(2);
        $this->aimlParser->checkSetParentTagIsInvalid();
        $this->assertFalse($tag->getIsTagValid());
    }


    /**
     * helper function to add some tags to the global stack
     */
    public function fillGlobalTagStack()
    {

        $this->aimlParser->setTagClass('Template', []);
        $this->aimlParser->initTemplateTagOnTagStack();
        $this->aimlParser->pushCurrentTagToTagStack();

        $this->aimlParser->setTagClass('Condition', []);
        $this->aimlParser->pushCurrentTagToTagStack();

        $this->aimlParser->setTagClass('Li', []);
        $this->aimlParser->pushCurrentTagToTagStack();
    }

    /**
     *
     */
    public function tearDown() :void
    {
        Mockery::close();
        $config = app('config');
        $this->artisan('config:clear');
        $this->artisan('cache:clear');
        $this->artisan('route:clear');
        ////$this->artisan('optimize');
        parent::tearDown();
        app()->instance('config', $config);
    }
}
