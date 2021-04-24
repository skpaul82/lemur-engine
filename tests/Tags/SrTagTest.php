<?php namespace Tests\Tags;

use App\Models\BotProperty;
use App\Models\Conversation;
use App\Models\Turn;
use App\Services\TalkService;
use App\Tags\Bot;
use App\Tags\SrTag;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TagTestCase;
use Tests\TestCase;
use UnitTestCategoriesTableSeeder;

class SrTagTest extends TagTestCase
{
    protected $parser;
    protected $mock;
    protected $conversation;
    protected $turn;
    protected $srTag;
    protected $talkService;

    public function setUp() :void
    {

        //some common parts of the conversation
        //are set up in the parent TagTestCase constructor
        parent::setUp();

        $this->conversation->shouldReceive('debug', 'setDebug');

        $this->conversation->shouldReceive('setVar')->withAnyArgs();
        $this->conversation->shouldReceive('getVar')->withArgs(['srai-count', 0])->andReturn(0);
        $this->conversation->shouldReceive('currentParentTurnId')->andReturn(1);


        $this->talkService = Mockery::mock(TalkService::class);


        $srConversation = Mockery::mock(Conversation::class);
        $srConversation->shouldReceive('getVars')->andReturn(['a'=>'b']);
        $srConversation->shouldReceive('setVar')->withArgs(['a','b']);


        $maxArr['stateMax']['openSrai']=10;

        $this->talkService->shouldReceive('getConfig')
            ->andReturn($maxArr);
        $this->talkService->shouldReceive('initFromTag', 'talk');
        $this->talkService->shouldReceive('getConversation')
            ->andReturn($srConversation);
        $this->talkService->shouldReceive('responseOutput')
            ->andReturn($this->mockResponse());
        $this->talkService->shouldReceive('getOutput')
            ->andReturn('test passed');
        $this->conversation->shouldReceive('getAttribute')
            ->withArgs(['bot_id'])->andReturn(1);
        $this->conversation->shouldReceive('getAttribute')
            ->withArgs(['client_id'])->andReturn(1);


        $this->srTag = new SrTag($this->talkService, $this->conversation, []);
    }


    /**
     * @return void
     */
    public function testGetTagName()
    {
        $this->assertEquals('Sr', $this->srTag->getTagName());
    }

    /**
     * test Bot::constructor()
     *
     * @return void
     */
    public function testConstructorWithNoAttributes()
    {
        $this->assertFalse($this->srTag->hasAttributes());
    }

    /**
     * test Bot::closeTag()
     *
     * @return void
     */
    public function testCloseTag()
    {
        $this->conversation->shouldReceive('countOpenSraiTags')->withAnyArgs()
            ->andReturn(0);
        $this->srTag->closeTag();
        $this->assertEquals('test passed', $this->srTag->getTagContents()[0]);
    }

    /**
     * test Bot::closeTag()
     *
     * @return void
     */
    public function testGetResponseFromNewTalk()
    {
        $this->conversation->shouldReceive('countOpenSraiTags')->withAnyArgs()
            ->andReturn(0);
        $res = $this->srTag->getResponseFromNewTalk("this is ignored because it is mocked");
        $this->assertEquals('test passed', $res);
    }

    public function testCheckMaxSrReachedReturnsFalseWhenMaxIsNotReached()
    {


        $this->conversation->shouldReceive('countOpenSraiTags')->withAnyArgs()
            ->andReturn(2);

        $this->assertFalse($this->srTag->checkMaxSraiReached());
    }

    public function testCheckMaxSrReachedReturnsTrueWhenMaxIsReached()
    {


        $this->conversation->shouldReceive('countOpenSraiTags')
            ->andReturn(14);

        $this->srTag->checkMaxSraiReached();
        $this->assertTrue($this->srTag->checkMaxSraiReached());
    }

    /**
     *
     */
    public function tearDown() :void
    {
        parent::tearDown();
    }


    public function mockResponse()
    {

        $response['res']['conversation']['input']='';
        $response['res']['conversation']['output']='';
        $response['res']['conversation']['id']='';
        $response['res']['conversation']['topic']='';

        $response['res']['bot']['id']='';
        $response['res']['bot']['name']='';
        $response['res']['bot']['image']='';

        $response['res']['client']['id']='';
        $response['res']['client']['name']='';
        $response['res']['client']['image']='';

        $response['features']=[];

        $response['debugArr']['wildcards']=[];
        $response['debugArr']['globals']=[];
        $response['debugArr']['locals']=[];
        $response['debugArr']['debug']=[];
        $response['debugArr']['admin']=[];

        return $response;
    }
}
