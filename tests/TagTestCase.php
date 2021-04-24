<?php

namespace Tests;

use App\Classes\TagStack;
use App\Models\Conversation;
use Mockery;

class TagTestCase extends TestCase
{
    public function setUp() :void
    {

        parent::setUp();
        $this->conversation = Mockery::mock(Conversation::class);
        $this->conversation->shouldReceive('getAttribute')
            ->withArgs(['id'])->andReturn(1);
        $this->conversation->shouldReceive('currentTurnId')
            ->andReturn(1);
        $this->conversation->shouldReceive('debug');
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

        parent::tearDown();
        app()->instance('config', $config);
    }
}
