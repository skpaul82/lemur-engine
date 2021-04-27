<?php
/**
 * Created by PhpStorm.
 * User: liseperu
 * Date: 16/08/2016
 * Time: 17:51
 *
 *
 * @AimlTag Lowercase
 * @AimlVersion 1.0,2.0
 * @AimlTagDescription Formats a string to upper lower case
 *
 */

namespace App\Tags\Custom;

use App\Classes\LemurLog;
use App\Tags\AimlTag;
use App\Models\Conversation;

/**
 * Class HelloworldTag
 * @package App\Tags\Custom
 *
 * Usage: <helloworld />
 *
 * Example AIML:
 * <category>
 *  <pattern>TEST</pattern>
 *  <template><helloworld /></template>
 * </category>
 *
 * Expected Conversation:
 * Input: Test
 * Output: Hello World!
 *
 * Documentation:
 * https://docs.lemurengine.com/extend.html
 */
class HelloworldTag extends AimlTag
{
    protected $tagName = "Helloworld";


    /**
     * HelloWorldTag Constructor.
     * @param Conversation $conversation
     * @param $attributes
     */
    public function __construct(Conversation $conversation, $attributes = [])
    {
        parent::__construct($conversation, $attributes);
    }


    /**
     * This method is called when the closing tag is encountered e.g. <helloworld/>
     * @return string|void
     */
    public function closeTag()
    {
        //some debugging
        LemurLog::debug(
            __FUNCTION__, [
                'conversation_id'=>$this->conversation->id,
                'turn_id'=>$this->conversation->currentTurnId(),
                'tag_id'=>$this->getTagId(),
                'attributes'=>$this->getAttributes()
            ]
        );

        //build response in the stack
        $this->buildResponse('Hello World!');
    }
}
