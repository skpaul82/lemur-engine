<?php
namespace App\Tags;

use App\Classes\LemurLog;
use App\Models\Conversation;

/**
 * Class TopicTag
 * @package App\Tags
 * Documentation on this tag, examples and explanation
 * see: https://docs.lemurengine.com/aiml.html
 */
class TopicTag extends AimlTag
{
    protected $tagName = "Topic";

    /**
     * TopicTag Constructor.
     * @param Conversation $conversation
     * @param $attributes
     */
    public function __construct(Conversation $conversation, $attributes = [])
    {

        parent::__construct($conversation, $attributes);
    }

    public function closeTag()
    {

                LemurLog::debug(
                    __FUNCTION__,
                    [
                    'conversation_id'=>$this->conversation->id,
                    'turn_id'=>$this->conversation->currentTurnId(),
                    'tag_id'=>$this->getTagId(),
                    'attributes'=>$this->getAttributes()
                    ]
                );

        // this has intentionally been left blank...
        // there is actually no such thing as a topic tag which is parsed run time
        // but we still need to run tests that aiml with topics can be matched correctly
    }
}
