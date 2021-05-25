<?php
namespace App\Tags;

use App\Classes\LemurLog;
use App\Models\Conversation;

/**
 * Class GetTag
 * @package App\Tags
 * Documentation on this tag, examples and explanation
 * see: https://docs.lemurengine.com/aiml.html
 */
class GetTag extends AimlTag
{
    protected $tagName = "Get";

    /**
     * GetTag Constructor.
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

        $contents = $this->getCurrentTagContents(true);

        if ($this->hasAttributes()) {
            if ($this->isInLiTag()) {
                //this will prevent the parsing of all the possible options inside the li tag
                //until the correct one has been identified
                $contents = $this->buildAIMLIfInDoNotParseMode($contents);
                $this->buildResponse($contents);
            } elseif ($this->hasAttribute('NAME')) {
                $name = $this->getAttribute('NAME');
                $get = $this->conversation->getGlobalProperty($name, '');

                $this->buildResponse($contents);
                $this->buildResponse($get);
            } elseif ($this->hasAttribute('VAR')) {
                $name = $this->getAttribute('VAR');
                $get = $this->conversation->getVar($name, '');

                $this->buildResponse($contents);
                $this->buildResponse($get);
            }
        }
    }
}
