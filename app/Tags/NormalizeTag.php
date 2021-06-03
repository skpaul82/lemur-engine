<?php
namespace App\Tags;

use App\Classes\LemurLog;
use App\Classes\LemurStr;
use App\Models\Conversation;

/**
 * Class NormalizeTag
 * @package App\Tags
 * Documentation on this tag, examples and explanation
 * see: https://docs.lemurengine.com/aiml.html
 */
class NormalizeTag extends AimlTag
{
    protected $tagName = "Normalize";


    /**
     * NormalizeTag Constructor.
     * @param Conversation $conversation
     * @param $attributes
     */
    public function __construct(Conversation $conversation, $attributes = [])
    {

        parent::__construct($conversation, $attributes);
    }


    /**
     * when we close the <set> tag we need to decide if we want
     */
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

        $contents = $this->getCurrentTagContents(true);

        $this->buildResponse(LemurStr::normalize($contents));
    }
}
