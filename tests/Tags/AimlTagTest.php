<?php namespace Tests\Tags;

use App\Classes\TagStack;
use App\Models\BotProperty;
use App\Models\Conversation;
use App\Services\TalkService;
use App\Tags\AimlTag;
use App\Tags\Bot;
use App\Tags\Evaluate;
use App\Tags\Learn;
use App\Tags\LearnTag;
use App\Tags\Li;
use App\Tags\LiTag;
use App\Tags\Template;
use App\Tags\TemplateTag;
use App\Tags\TestTag;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Mockery;
use Tests\TagTestCase;
use Tests\TestCase;
use UnitTestCategoriesTableSeeder;

class AimlTagTest extends TagTestCase
{
    protected $parser;
    protected $mock;
    protected $conversation;
    protected $talkService;
    protected $botProperties;
    protected $testTag;


    public function setUp() :void
    {

        parent::setUp();

        $this->testTag = new TestTag($this->conversation, ['var_1'=>'this','var_2'=>'that']);

        $this->talkService = Mockery::mock(TalkService::class);
    }


    /**
     * A basic test example.
     *
     * @return void
     */
    public function testConstructor()
    {
        $this->assertTrue($this->testTag->hasAttributes());
        $this->assertTrue($this->testTag->hasAttribute('var_1'));
        $this->assertTrue($this->testTag->hasAttribute('var_2'));
        $this->assertEquals('this', $this->testTag->getAttribute('var_1'));
        $this->assertEquals('that', $this->testTag->getAttribute('var_2'));
        $this->assertEmpty($this->testTag->getTagContents());
    }

    /**
     * @return void
     */
    public function testGetMember()
    {
        $this->assertEquals('something', $this->testTag->getMember('someVar'));
    }



    /**
     * @return void
     */
    public function testGetIsTagValid()
    {
        $this->assertTrue($this->testTag->getIsTagValid());
    }

    /**
     * @return void
     */
    public function testSetIsTagValid()
    {
        $this->testTag->setIsTagValid(false);
        $this->assertFalse($this->testTag->getIsTagValid());
    }

    /**
     * @return void
     */
    public function testGetTagId()
    {
        $this->testTag->setTagId('aimlTag_0');
        $this->assertEquals('aimlTag_0', $this->testTag->getTagId());
    }

    public function testGetTagName()
    {
        $this->assertEquals('Test', $this->testTag->getTagName());
    }


    public function testSetAttributes()
    {

        $this->testTag->setAttributes(['var_3'=>'a','var_4'=>'b']);
        $this->assertTrue($this->testTag->hasAttribute('var_3'));
        $this->assertEquals('a', $this->testTag->getAttribute('var_3'));
        $this->assertEquals('b', $this->testTag->getAttribute('var_4'));
        $this->assertFalse($this->testTag->hasAttribute('var_5'));
    }


    public function testGetAttributes()
    {

        $this->testTag->setAttributes(['var_3'=>'a','var_4'=>'b']);
        $this->assertEquals(4, count($this->testTag->getAttributes()));
        $this->assertTrue(in_array('a', $this->testTag->getAttributes()));
        $this->assertTrue(in_array('that', $this->testTag->getAttributes()));
        $this->assertTrue(in_array('var_3', array_flip($this->testTag->getAttributes())));
        $this->assertTrue(in_array('var_1', array_flip($this->testTag->getAttributes())));
    }

    public function testHasAttributes()
    {
        $this->assertTrue($this->testTag->hasAttributes());
    }



    public function testGetAttribute()
    {
        $this->assertEquals('this', $this->testTag->getAttribute('var_1'));
        $this->assertEquals('that', $this->testTag->getAttribute('var_2'));
    }


    public function testHasAttribute()
    {
        $this->assertTrue($this->testTag->hasAttribute('var_1'));
        $this->assertFalse($this->testTag->hasAttribute('xxxxx'));
    }

    public function testGetTagSetting()
    {
        $this->assertEquals('blank', $this->testTag->getTagSetting('test', 'blank'));
    }


    public function testOpenTag()
    {
        $this->testTag->openTag(['setting_a'=>'a']);
        $this->assertEquals('a', $this->testTag->getTagSetting('setting_a', 'b'));
    }

    public function testCloseTag()
    {
        $this->testTag->setTagContents("1 2 3");
        $this->testTag->setTagContents("A B C");
        $this->testTag->closeTag();
        $this->assertEquals('1 2 3 A B C', $this->testTag->getTagContents()[0]);
    }

    public function testProcessContentsWithArray()
    {

        $this->testTag->processContents(['Hi','my name is foo!']);
        $this->assertEquals('Hi my name is foo!', $this->testTag->getTagContents()[0]);
    }


    /**
     * @param $tag
     * @param $rootTag
     * @return mixed
     * @throws Exception
     */
    public function setUpDummyTags($tag, $rootTag = false)
    {

        //make sure we have an template tag...
        //if not set one up
        if ($rootTag) {
            $testTag = new TemplateTag($this->conversation, []);
            //create a tagId
            $tagId = 'TemplateTag_0_123';
            $testTag->setTagId($tagId);
            $testTag->getTagStack()->incIndex($tagId);
            $testTag->getTagStack()->push($testTag, $tagId);
        } else {
            $tagClass = "App\\Tags\\".$tag."Tag";

            $recursiveTags = config('lemur_tag.recursive');

            if (in_array(strtolower($tag), $recursiveTags)) {
                $testTag = new $tagClass($this->talkService, $this->conversation, ['var_1'=>'this','var_2'=>'that']);
            } else {
                $testTag = new $tagClass($this->conversation, ['var_1'=>'this','var_2'=>'that']);
            }

            $this->assertEquals($tag, $testTag->getTagName());

            //create a tagId
            $tagId = $tag.'_'.$testTag->getTagStack()->count().'_123';
            $testTag->setTagId($tagId);
            $testTag->getTagStack()->push($testTag, $tagId);
        }



        return $testTag;
    }

    public function testIsInLearningModeForFirstLi()
    {

        $this->setUpDummyTags('Template', true);
        $testTag = $this->setUpDummyTags('Li');
        $this->assertFalse($testTag->isInLearningMode());
    }

    public function testIsInLearningModeForFirstLearn()
    {

        $this->setUpDummyTags('Template', true);
        $testTag = $this->setUpDummyTags('Learn');
        $this->assertTrue($testTag->isInLearningMode('ddd'));
    }

    public function testIsInLearningModeForSecondLi()
    {

        $this->setUpDummyTags('Template', true);
        $this->setUpDummyTags('Condition');
        $testTag = $this->setUpDummyTags('Li');
        $this->assertFalse($testTag->isInLearningMode());
    }


    public function testIsInLearningModeForSecondLearn()
    {

        $this->setUpDummyTags('Template', true);
        $testTag =  $this->setUpDummyTags('Learn');
        $this->assertTrue($testTag->isInLearningMode());
    }

    public function testIsInLearningModeForThirdLearn()
    {

        $this->setUpDummyTags('Template', true);
        $this->setUpDummyTags('Li');
        $testTag = $this->setUpDummyTags('Learn');
        $this->assertTrue($testTag->isInLearningMode());
    }



    public function testIsInLiTagForFirstLi()
    {

        $this->setUpDummyTags('Template', true);
        $testTag = $this->setUpDummyTags('Li');
        $this->assertTrue($testTag->isInLiTag());
    }

    public function testIsInLiTagForFirstLearn()
    {

        $this->setUpDummyTags('Template', true);
        $testTag = $this->setUpDummyTags('Learn');
        $this->assertFalse($testTag->isInLiTag());
    }

    public function testIsInLiTagForSecondLi()
    {

        $this->setUpDummyTags('Template', true);
        $testTag = $this->setUpDummyTags('Li');
        $this->assertTrue($testTag->isInLiTag());
    }


    public function testIsInLiTagForSecondLearn()
    {

        $this->setUpDummyTags('Template', true);
        $testTag = $this->setUpDummyTags('Learn');
        $this->assertFalse($testTag->isInLiTag());
    }

    public function testIsInLiTagForEmbeddedLiAndLearn()
    {

        $testTag = $this->setUpDummyTags('Template', true);
        $this->setUpDummyTags('Li');
        $this->setUpDummyTags('Learn');
        $this->assertTrue($testTag->isInLiTag());
    }

    public function testIsInLiTagForNoLiTag()
    {

        $this->setUpDummyTags('Template', true);
        $this->setUpDummyTags('Condition');
        $testTag = $this->setUpDummyTags('Learn');
        $this->assertFalse($testTag->isInLiTag());
    }


    public function testBuildAimlIfInLearnMode()
    {

        $this->setUpDummyTags('Template', true);
        $this->setUpDummyTags('Li');
        $testTag = $this->setUpDummyTags('Learn');
        $checkAiml = $testTag->buildAIMLIfInLearnMode('foo');
        $this->assertEquals('<learn var_1="this" var_2="that">foo</learn>', $checkAiml);
    }

    public function testBuildAimlIfInLiModeSuccess()
    {

        $this->setUpDummyTags('Template', true);
        $this->setUpDummyTags('Li');
        $testTag = $this->setUpDummyTags('Learn');
        $checkAiml = $testTag->buildAIMLIfInLiMode('foo');
        $this->assertEquals('<learn var_1="this" var_2="that">foo</learn>', $checkAiml);
    }


    public function testBuildAimlIfInDoNotParseMode()
    {

        $checkAiml = $this->testTag->buildAIMLIfInDoNotParseMode('bob');
        $this->assertEquals('<test var_1="this" var_2="that">bob</test>', $checkAiml);
    }

    public function testGetPreviousTagObjectWithTagName()
    {

        $this->setUpDummyTags('Template', true);
        $this->setUpDummyTags('Li');
        $testTag = $this->setUpDummyTags('Learn');

        $previousObject = $testTag->getPreviousTagObject('Template');
        $this->assertEquals('Template', $previousObject->getTagName());
        $this->assertInstanceOf(TemplateTag::class, $previousObject);
    }

    public function testGetPreviousTagObjectWithNoTagName()
    {

        $this->setUpDummyTags('Template', true);
        $this->setUpDummyTags('Li');
        $testTag = $this->setUpDummyTags('Learn');

        $previousObject = $testTag->getPreviousTagObject();
        $this->assertEquals('Li', $previousObject->getTagName());
        $this->assertInstanceOf(LiTag::class, $previousObject);
    }



    public function testGetPreviousTagClassFromStack()
    {

        $this->setUpDummyTags('Template', true);
        $this->setUpDummyTags('Li');
        $testTag = $this->setUpDummyTags('Learn');
        $previousObject = $testTag->getPreviousTagClassFromStack();
        $this->assertEquals('Li', $previousObject->getTagName());
        $this->assertIsObject($previousObject);
        $this->assertInstanceOf(LiTag::class, $previousObject);
    }

    public function testGetCurrentTagClassFromStack()
    {

        $this->setUpDummyTags('Template', true);
        $this->setUpDummyTags('Li');
        $testTag = $this->setUpDummyTags('Learn');
        $tagObject = $testTag->getTagClassFromStack('current');
        $this->assertEquals('Learn', $tagObject->getTagName());
        $this->assertIsObject($tagObject);
        $this->assertInstanceOf(LearnTag::class, $tagObject);
    }

    public function testGetCurrentPreviousTagClassFromStack()
    {

        $this->setUpDummyTags('Template', true);
        $this->setUpDummyTags('Li');
        $testTag = $this->setUpDummyTags('Learn');
        $tagObject = $testTag->getTagClassFromStack('previous');
        $this->assertEquals('Li', $tagObject->getTagName());
        $this->assertIsObject($tagObject);
        $this->assertInstanceOf(LiTag::class, $tagObject);
    }








    public function testGetTagContents()
    {
        $this->testTag->setTagContents('foo');
        $this->assertEquals('foo', $this->testTag->getTagContents()[0]);
    }

    public function testSetTagContents()
    {

        $this->testTag->setTagContents('foo');
        $this->testTag->setTagContents('bar');
        $this->assertEquals(['foo','bar'], $this->testTag->getTagContents());
    }



    /**
     *
     */
    public function tearDown() :void
    {

        //clear the tagstack after each test
        TagStack::getInstance()->destroy();
        parent::tearDown();
    }
}
