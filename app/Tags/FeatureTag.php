<?php
namespace App\Tags;

use App\Classes\LemurLog;
use App\Models\Conversation;

/**
 * Class FeatureTag
 * @package App\Tags
 * Documentation on this tag, examples and explanation
 * see: https://docs.lemurengine.com/aiml.html
 */
class FeatureTag extends AimlTag
{
    protected $tagName = "Feature";

    /**
     * FeatureTag Constructor.
     * @param Conversation $conversation
     * @param $attributes
     */
    public function __construct(Conversation $conversation, $attributes = [])
    {
        parent::__construct($conversation, $attributes);
    }


    /**
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

        if ($this->hasAttribute('NAME')) {
            $name = $this->getAttribute('NAME');
            $this->conversation->setFeature($name, $contents);
        }
    }
}
