<?php namespace Tests\Classes;

use App\Classes\LemurStr;
use Tests\TestCase;

class LemurStrTest extends TestCase
{
    /**
     *
     */
    public function setUp(): void
    {
        parent::setup();
    }

    /**
     * @test
     */
    public function testSplitIntoSentencesWithSingleWords()
    {
        $sentences = LemurStr::splitIntoSentences("one. two! three?");
        $this->assertIsArray($sentences);
        $this->assertEquals(['one','two','three'], $sentences);
    }

    /**
     * @test
     */
    public function testSplitIntoSentencesWithMultipleWords()
    {
        $sentences = LemurStr::splitIntoSentences("one two. two three! three four?");
        $this->assertIsArray($sentences);
        $this->assertEquals(['one two','two three','three four'], $sentences);
    }

    /**
     * @test
     */
    public function testSplitIntoSingleSentencesWithSingleWords()
    {
        $sentences = LemurStr::splitIntoSentences("one.");
        $this->assertIsArray($sentences);
        $this->assertEquals(['one'], $sentences);
    }

    /**
     * @test
     */
    public function testSplitIntoSingleSentencesWithMultipleWords()
    {
        $sentences = LemurStr::splitIntoSentences("one two.");
        $this->assertIsArray($sentences);
        $this->assertEquals(['one two'], $sentences);
    }

    /**
     * @test
     */
    public function testSplitIntoSingleSentencesWithSingleWordsWithoutPunctuation()
    {
        $sentences = LemurStr::splitIntoSentences("one");
        $this->assertIsArray($sentences);
        $this->assertEquals(['one'], $sentences);
    }

    /**
     * @test
     */
    public function testSplitIntoSingleSentencesWithMultipleWordsWithoutPunctuation()
    {
        $sentences = LemurStr::splitIntoSentences("one two");
        $this->assertIsArray($sentences);
        $this->assertEquals(['one two'], $sentences);
    }

    /**
     * @test
     */
    public function testNormalizeSimple()
    {
        $str = LemurStr::normalize("one two");
        $this->assertIsString($str);
        $this->assertEquals('ONE TWO', $str);
    }

    /**
     * @test
     */
    public function testNormalizeWithPunctuation()
    {
        $str = LemurStr::normalize("   5!@#$%^&*&*()'.,   hhHH  ");
        $this->assertIsString($str);
        $this->assertEquals('5 HHHH', $str);
    }

    /**
     * @test
     */
    public function testNormalizeSimpleWithSpace()
    {
        $str = LemurStr::normalize(" foo bar ");
        $this->assertIsString($str);
        $this->assertEquals('FOO BAR', $str);
    }

    /**
     * @test
     */
    public function testRemoveSentenceEnders()
    {
        $str = LemurStr::removeSentenceEnders(" foo bar.... ");
        $this->assertIsString($str);
        $this->assertEquals(' foo bar', $str);

        $str = LemurStr::removeSentenceEnders(" foo.. bar.... ");
        $this->assertIsString($str);
        $this->assertEquals(' foo.. bar', $str);

        $str = LemurStr::removeSentenceEnders("foo bar$$ ");
        $this->assertIsString($str);
        $this->assertEquals('foo bar', $str);

        $str = LemurStr::removeSentenceEnders("foo.. bar?! ");
        $this->assertIsString($str);
        $this->assertEquals('foo.. bar', $str);

        $str = LemurStr::removeSentenceEnders("1.2 million! ");
        $this->assertIsString($str);
        $this->assertEquals('1.2 million', $str);
    }


    /**
     * @test
     */
    public function testMbUcfirst()
    {
        $str = LemurStr::mbUcfirst(" foo bar.... ");
        $this->assertIsString($str);
        $this->assertEquals('Foo bar....', $str);

        $str = LemurStr::mbUcfirst("xxxxX");
        $this->assertIsString($str);
        $this->assertEquals('XxxxX', $str);
    }


    /**
     * @dataProvider regexpReplacerTestProvider
     * @test
     * @param $input
     * @param $output
     */
    public function testConvertToRegExpPattern($input, $output)
    {
        $str = LemurStr::convertToRegExpPattern($input);
        $this->assertIsString($str);
        $this->assertEquals($output, $str);
    }

    /**
     * @dataProvider wildcardTestProvider
     * @test
     * @param $string
     * @param $regExpItem
     * @param $expectArr
     */
    public function testextractWildcardFromString($string, $regExpItem, $expectArr)
    {
        $arr = LemurStr::extractWildcardFromString($string, $regExpItem);
        $this->assertIsArray($arr);
        $this->assertEquals($expectArr, $arr);
    }

    /**
     * @dataProvider stringsToNormaliseForCategoryTable
     * @test
     * @param $string
     * @param $tags
     * @param $expectStr
     */
    public function testNormalizeForCategoryTable($string, $tags, $expectStr)
    {

        $str = LemurStr::normalizeForCategoryTable($string, $tags);
        $this->assertIsString($str);
        $this->assertEquals($expectStr, $str);
    }
//new code begin 
  
    /**
     * @dataProvider replaceWildCardsInPatternProvider
     * @test
     * @param $string
     * @param $expectStr
     */
    public function testReplaceWildCardsInPattern($string, $expectStr)
    {
        $str = LemurStr::replaceWildCardsInPattern($string);
        $this->assertIsString($str);
        $this->assertEquals($expectStr, $str);
    }

    
    /**
     *@test
     */
    public function testGetFirstCharFromStr()
    {
        $str = LemurStr::getFirstCharFromStr("Foo");
        $this->assertIsString($str);
        $this->assertEquals('F', $str);
    }


    /**
     *@test
     */   
    public function testCreateRegExpFromString()
    {
        $str = LemurStr::createRegExpFromString('Foo*bar');
        $this->assertIsString($str);
        $this->assertEquals("Foo(.*)bar", $str);

        $str = LemurStr::createRegExpFromString('Foo_bar');
        $this->assertIsString($str);
        $this->assertEquals('Foo(.*)bar', $str);

        $str = LemurStr::createRegExpFromString('Foo^bar');
        $this->assertIsString($str);
        $this->assertEquals('Foo(*+)bar', $str);

        $str = LemurStr::createRegExpFromString('Foo#bar');
        $this->assertIsString($str);
        $this->assertEquals('Foo(*+)bar', $str);

        $str = LemurStr::createRegExpFromString('Foo$bar');
        $this->assertIsString($str);
        $this->assertEquals('Foobar', $str);

    }

    /**
     *@test 
     */
    public function testConvertStrToRegExp()
    {
        $str = LemurStr::convertStrToRegExp("foo* bar");
        $this->assertIsString($str);
        $this->assertEquals("foo(.*) bar", $str);

        $str = LemurStr::convertStrToRegExp("foo# bar");
        $this->assertIsString($str);
        $this->assertEquals("foo(.*)?bar", $str);

        $str = LemurStr::convertStrToRegExp("foo_bar");
        $this->assertIsString($str);
        $this->assertEquals("foo(.*)bar", $str);

        $str = LemurStr::convertStrToRegExp("foo (\\sbar");
        $this->assertIsString($str);
        $this->assertEquals("foo(\\sbar", $str);

    }

    /**
     *@test
     */
    public function testCleanAndImplode()
    {
        $str = LemurStr::cleanAndImplode(['Happy', 'Hopefull', 'crazy', 'joyfull']);
        $this->assertIsString($str);
        $this->assertEquals("Happy Hopefull crazy joyfull",$str);
    }

    /**
     *@test
     */ 
    public function testCleanOutPutForResponse()
    {
        $str = LemurStr::cleanAndImplode(".?!");
        $this->assertIsString($str);
        $this->assertEquals(".?!",$str);

        $str = LemurStr::cleanAndImplode('    ');
        $this->assertIsString($str);
        $this->assertEquals('',$str);

    }
//new code end

// Data Providers Begin

    public function replaceWildCardsInPatternProvider()
    {
        return [
            'middle ^' => ['foo ^ bar','foo%bar'],
            'middle #' => ['foo # bar','foo%bar'],
            'left ^' => ['foo^ bar','foo%bar'],
            'left #' => ['foo# bar','foo%bar'],
            'right ^' => ['foo ^bar','foo%bar'],
            'right #' => ['foo #bar','foo%bar'],
            'nospaces ^' => ['foo^bar','foo%bar'],
            'nospaces #' => ['foo#bar','foo%bar'],
            'asterisk' => ['foo*bar','foo%bar'],
            'underline' => ['foo_bar','foo%bar'],
            'dollar' => ['bar$foo','barfoo'],

            'test 1' => ['I want ^ noodles', 'I want%noodles'],
            'test 2' => ['I want^ noodles', 'I want%noodles'],
            'test 3' => ['I want ^noodles', 'I want%noodles'],
            'test 4' => ['I want^noodles', 'I want%noodles'],


            'test 5' => ['I want # noodles', 'I want%noodles'],
            'test 6' => ['I want# noodles', 'I want%noodles'],
            'test 7' => ['I want #noodles', 'I want%noodles'],
            'test 8' => ['I want#noodles', 'I want%noodles'],


            'test 9' => ['I want * noodles', 'I want % noodles'],
            'test 10' => ['I want* noodles', 'I want% noodles'],
            'test 11' => ['I want *noodles', 'I want %noodles'],
            'test 12' => ['I want*noodles', 'I want%noodles'],


            'test 13' => ['I want _ noodles', 'I want % noodles'],
            'test 14' => ['I want_ noodles', 'I want% noodles'],
            'test 15' => ['I want _noodles', 'I want %noodles'],
            'test 16' => ['I want_noodles', 'I want%noodles'],

            'test 17' => ['$I want noodles', 'I want noodles'],

            'test 18' => ['I want <set>food</set>', 'I want %'],
            'test 19' => ['I want <bot name="name" />', 'I want %'],


            'test 20' => ['<set>food</set> I want', '% I want'],
            'test 21' => ['<bot name="name" /> I want', '% I want'],


            'test 22' => ['<set>food</set> IS <set>food</set>', '% IS %'],
            'test 23' => ['<bot name="name" /> IS <bot name="name" />', '% IS %'],

            'test 24' => ['<set>food</set> IS <bot name="name" />', '% IS %'],

            'test 25' => ['<BOT NAME="NAME" />', '%'],
            'test 26' => ['<SET>COOL</SET>', '%'],
            'test 27' => ['<BOT NAME="NAME" /> IS <SET>COOL</SET>', '% IS %'],

            'test 28' => ['<set>food</set> <bot name="name" />', '% %'],
        ];
    }

    public function stringsToNormaliseForCategoryTable()
    {
        return [


            'test 1' => ['TEST <set>A</set>&<set>B</set>? OR C?!', ['set','bot'], 'TEST <SET>A</SET> <SET>B</SET> OR C'],
            'test 2' => ['TEST <set>A</set>&<set>B</set>? OR C?!', ['fake'], 'TEST A B OR C'],
            'test 3' => ['TEST <set>A</set>&<set>B</set>? OR C?!', [], 'TEST A B OR C'],
            'test 4' => ['TEST <set>A</set>&<bot>B</bot>? OR C?!', ['set','bot'], 'TEST <SET>A</SET> <BOT>B</BOT> OR C'],
            'test 5' => ['TEST <set>A</set>&<bot name="name" />? OR C?!', ['set','bot'], 'TEST <SET>A</SET> <BOT NAME="NAME" /> OR C'],
            'test 6' => ['TEST <set>A</set> <bot name="name" /> <bot name="place" />', ['set','bot'], 'TEST <SET>A</SET> <BOT NAME="NAME" /> <BOT NAME="PLACE" />'],
            'test 7' => ['TEST <set>B</set>? OR ="C".', ['set'], 'TEST <SET>B</SET> OR C'],


        ];
    }





    public function wildcardTestProvider()
    {
        return [

            'I am % and %'=>['I AM HAPPY AND HOPEFUL', 'I AM % AND %', ['HAPPY','HOPEFUL']],
            'I am %'=>['I AM HAPPY', 'I AM %', ['HAPPY']],
        ];
    }


    public function regexpReplacerTestProvider()
    {
        return [
            'I am %'=>['I AM %', '#I AM (.*)#i'],
            'I am % and %'=>['I AM % AND %', '#I AM (.*) AND (.*)#i'],
            'start % % % % five'=>['START % % % % FIVE', '#START (\w+)\s(\w+)\s(\w+)\s(\w+)\sFIVE#i'],
            'big juicy % % % % %'=>['BIG JUICY % % % % %', '#BIG JUICY (\w+)\s(\w+)\s(\w+)\s(\w+)\s(.*)#i'],


            'I am (.*)'=>['I AM (.*)', '#I AM (.*)#i'],
            'I am (.*) and (.*)'=>['I AM (.*) AND (.*)', '#I AM (.*) AND (.*)#i'],
            'start (.*) (.*) (.*) (.*) five'=>['START (.*) (.*) (.*) (.*) FIVE', '#START (\w+)\s(\w+)\s(\w+)\s(\w+)\sFIVE#i'],
            'big juicy (.*) (.*) (.*) (.*) (.*)'=>['BIG JUICY (.*) (.*) (.*) (.*) (.*)', '#BIG JUICY (\w+)\s(\w+)\s(\w+)\s(\w+)\s(.*)#i'],
        ];
    }
//Data Providers End

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
