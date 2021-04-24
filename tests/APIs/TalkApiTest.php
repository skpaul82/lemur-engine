<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\Bot;

class TalkApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testTalk()
    {
        $bot = factory(Bot::class)->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/bots',
            $bot
        );

        $this->assertApiResponse($bot);
    }

    /**
     * @test
     */
    public function testMeta()
    {
        $bot = factory(Bot::class)->create();

        $this->response = $this->json(
            'GET',
            '/api/bots/'.$bot->id
        );

        $this->assertApiResponse($bot->toArray());
    }
}
