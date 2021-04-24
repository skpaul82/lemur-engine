<?php namespace Tests\Classes;

use App\Classes\TagStack;
use Illuminate\Support\Facades\App;
use ReflectionClass;
use Tests\CreatesApplication;
use Tests\TestCase;

class TagStackTest extends TestCase
{
    protected $stack;
    protected $conversation;
    protected $parser;
    protected $mock;
    protected $originalStack;
    protected $botTag;


    public function setUp() :void
    {

        parent::setUp();

        TagStack::getInstance()->destroy();

        $this->originalStack = TagStack::getInstance();
    }




    public function testPushWithoutInitAndExpectException()
    {
        $stack = clone($this->originalStack);
        $this->expectException("Exception");

        $class = new \stdClass();

        $stack->push($class, 'a');
    }

    public function testIncIndex()
    {
        $stack = clone($this->originalStack);
        $stack->incIndex('a');
        $this->assertEquals('a', $stack->getIndex());
    }

    public function testPushOnce()
    {
        $stack = clone($this->originalStack);
        $classA = (object)['name'=>'foo'];
        $stack->incIndex('a');
        $stack->push($classA, 'a');
        $res = $stack->getStack();
        $this->assertEquals(1, count($res['a']));
    }

    public function testPushTwice()
    {
        $stack = clone($this->originalStack);
        $classA = (object)['name'=>'foo'];
        $stack->incIndex('a');
        $stack->push($classA, 'a');
        $res = $stack->getStack();
        $this->assertEquals(1, count($res['a']));

        $classB = (object)['name'=>'bar'];
        $stack->push($classB, 'b');
        $res = $stack->getStack();
        $this->assertEquals(2, count($res['a']));
    }


    public function testPushTwiceThenPopOne()
    {
        $stack = clone($this->originalStack);
        $classA = (object)['name'=>'foo'];
        $stack->incIndex('a');
        $stack->push($classA, 'a');
        $res = $stack->getStack();
        $this->assertEquals(1, count($res['a']));

        $classB = (object)['name'=>'bar'];
        $stack->push($classB, 'b');
        $res = $stack->getStack();
        $this->assertEquals(2, count($res['a']));

        $stack->pop();
        $res = $stack->getStack();
        $this->assertEquals(1, count($res['a']));
    }

    public function testPushTwiceThenGetLastItemFromStack()
    {
        $stack = clone($this->originalStack);
        $classA = (object)['name'=>'foo'];
        $stack->incIndex('a');
        $stack->push($classA, 'a');
        $res = $stack->getStack();
        $this->assertEquals(1, count($res['a']));

        $classB = (object)['name'=>'bar'];
        $stack->push($classB, 'b');
        $res = $stack->getStack();
        $this->assertEquals(2, count($res['a']));

        $res = $stack->lastItem();
        $this->assertEquals('bar', $res->name);
    }

    public function testPushTwiceThenGetPreviousItemFromStack()
    {
        $stack = clone($this->originalStack);
        $classA = (object)['name'=>'foo'];
        $stack->incIndex('a');
        $stack->push($classA, 'a');
        $res = $stack->getStack();
        $this->assertEquals(1, count($res['a']));

        $classB = (object)['name'=>'bar'];
        $stack->push($classB, 'b');
        $res = $stack->getStack();
        $this->assertEquals(2, count($res['a']));

        $res = $stack->previousItem();
        $this->assertEquals('foo', $res->name);
    }

    public function testIsFinal()
    {
        $stack = clone($this->originalStack);
        $classA = (object)['name'=>'foo'];
        $stack->incIndex('a');
        $stack->push($classA, 'a');
        $res = $stack->getStack();
        $this->assertEquals(1, count($res['a']));

        $classB = (object)['name'=>'bar'];
        $stack->push($classB, 'b');
        $res = $stack->getStack();
        $this->assertEquals(2, count($res['a']));

        $this->assertFalse($stack->isFinalTag());

        $stack->pop();
        $res = $stack->getStack();
        $this->assertEquals(1, count($res['a']));

        $this->assertTrue($stack->isFinalTag());
    }

    public function testCountStack()
    {
        $stack = clone($this->originalStack);
        $stack->incIndex('a');
        $this->assertEquals(0, $stack->count());

        $classA = (object)['name'=>'foo'];
        $stack->push($classA, 'a');
        $this->assertEquals(1, $stack->count());

        $classB = (object)['name'=>'bar'];
        $stack->push($classB, 'b');

        $this->assertEquals(2, $stack->count());

        $classC = (object)['name'=>'hah'];
        $stack->push($classC, 'c');
        $this->assertEquals(3, $stack->count());

        $res = $stack->pop();
        $this->assertIsObject($res);
        $this->assertEquals('hah', $res->name);
        $this->assertEquals(2, $stack->count());

        $res = $stack->pop();
        $this->assertIsObject($res);
        $this->assertEquals('bar', $res->name);
        $this->assertEquals(1, $stack->count());

        $res = $stack->pop();
        $this->assertIsObject($res);
        $this->assertEquals('foo', $res->name);
        $this->assertEquals(0, $stack->count());

        $res = $stack->pop();
        $this->assertFalse($res);
    }


    public function testGettingItemByIndex()
    {
        $stack = clone($this->originalStack);
        $stack->incIndex('a');
        $this->assertEquals(0, $stack->count());

        $classA = (object)['name'=>'foo'];
        $stack->push($classA, 'a');
        $this->assertEquals(1, $stack->count());

        $classB = (object)['name'=>'bar'];
        $stack->push($classB, 'b');

        $this->assertEquals(2, $stack->count());

        $classC = (object)['name'=>'hah'];
        $stack->push($classC, 'c');
        $this->assertEquals(3, $stack->count());

        $res = $stack->item(1);
        $this->assertEquals('bar', $res->name);

        $res = $stack->item(2);
        $this->assertEquals('hah', $res->name);


        $res = $stack->item(0);
        $this->assertEquals('foo', $res->name);

        $this->expectException("Exception");
        $stack->item(5);
    }




    public function testMaxPosition()
    {
        $stack = clone($this->originalStack);
        $stack->incIndex('a');
        $this->assertFalse($stack->maxPosition());

        $classA = (object)['name'=>'foo'];
        $stack->push($classA, 'a');
        $this->assertEquals(0, $stack->maxPosition());

        $classB = (object)['name'=>'bar'];
        $stack->push($classB, 'b');
        $this->assertEquals(1, $stack->maxPosition());

        $classC = (object)['name'=>'hah'];
        $stack->push($classC, 'c');
        $this->assertEquals(2, $stack->maxPosition());

        $stack->pop();
        $this->assertEquals(1, $stack->maxPosition());

        $stack->pop();
        $this->assertEquals(0, $stack->maxPosition());

        $stack->pop();
        $this->assertFalse($stack->maxPosition());
    }


    public function testOverWrite()
    {
        $stack = clone($this->originalStack);
        $stack->incIndex('a');
        $this->assertEquals(0, $stack->count());

        $classA = (object)['name'=>'foo'];
        $stack->push($classA, 'a');

        $classB = (object)['name'=>'bar'];
        $stack->push($classB, 'b');

        $res = $stack->item(1);
        $this->assertEquals('bar', $res->name);

        $classC = (object)['name'=>'hah'];
        $stack->overWrite($classC);
        $res = $stack->item(1);
        $this->assertEquals('hah', $res->name);
    }

    public function testDecIndex()
    {
        $stack = clone($this->originalStack);
        $stack->incIndex('a');
        $this->assertEquals('a', $stack->getIndex());
        $stack->incIndex('b');
        $this->assertEquals('b', $stack->getIndex());
        $stack->decIndex('b');
        $this->assertEquals('a', $stack->getIndex());
    }


    /**
     *
     */
    public function tearDown() :void
    {
        $config = app('config');
        $this->artisan('config:clear');
        $this->artisan('cache:clear');
        $this->artisan('route:clear');
        ////$this->artisan('optimize');
        parent::tearDown();
        app()->instance('config', $config);
    }
}
