<?php
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
    protected $config;


    /**
     * HelloWorldTag Constructor.
     * @param Conversation $conversation
     * @param $attributes
     */
    public function __construct(Conversation $conversation, $attributes = [])
    {
        parent::__construct($conversation, $attributes);
        $this->config = config('custom.helloworld');
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
        $this->buildResponse($this->config['message']);
    }
}
