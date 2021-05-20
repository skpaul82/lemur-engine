<?php namespace Tests\APIs;

use App\Models\User;
use App\Policies\BotCategoryGroupPolicy;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\Bot;

class TalkApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    protected $user;
    protected $bot;

    /**
     * Setup
     */
    public function setUp(): void
    {

        parent::setUp();

        //create an author user.....
        $this->user = factory(User::class)->create();
        $this->user->assignRole('admin');

        //set to the user we are testing
        $this->be($this->user);

        $this->bot = factory(Bot::class)->create(['language_id'=>1, 'is_public'=>1, 'status'=>'A', 'user_id'=>$this->user->id]);
    }

    /**
     * @test
     */
    public function testTalk()
    {

        $post['client']='123';
        $post['bot']=$this->bot->slug;
        $post['html']=1;
        $post['message']='test';

        $this->response = $this->json(
            'POST',
            '/api/talk/bot',
            $post
        );

        $this->assertApiSuccess();
    }

    /**
     * @test
     */
    public function testTalkNotFound()
    {

        $post['client']='123';
        $post['bot']=uniqid('fake_talk_', false);
        $post['html']=1;
        $post['message']='test';

        $this->response = $this->json(
            'POST',
            '/api/talk/bot',
            $post
        );

        $this->assertApi404();
    }


    /**
     * @test
     */
    public function testMeta()
    {

        $post['client']='123';
        $post['bot']=$this->bot->slug;

        $this->response = $this->json(
            'POST',
            '/api/talk/meta',
            $post
        );

        $this->assertApiSuccess();
    }


    /**
     * @test
     */
    public function testMetaNotFound()
    {

        $post['client']='123';
        $post['bot']=uniqid('fake_meta_', false);

        $this->response = $this->json(
            'POST',
            '/api/talk/meta',
            $post
        );

        $this->assertApi404();
    }
}
