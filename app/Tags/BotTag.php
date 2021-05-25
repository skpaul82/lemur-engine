<?php
namespace App\Tags;

use App\Classes\LemurLog;
use App\Models\Conversation;

/**
 * Class BotTag
 * @package App\Tags
 * Documentation on this tag, examples and explanation
 * see: https://docs.lemurengine.com/aiml.html
 */
class BotTag extends AimlTag
{
    protected $tagName = "Bot";

    /**
     * BotTag Constructor.
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

            //is the name attribute set?
        if ($this->hasAttribute('NAME')) {
            //get the name value
            $nameValue = $this->getAttribute('NAME');

            $value = $this->getBotProperty($nameValue);


            $this->buildResponse($value);
        }
    }


    /**
     * @param $nameValue
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function getBotProperty($nameValue)
    {


        $botProperty = $this->conversation->getBotProperty($nameValue);

        if ($botProperty === null) {
            $botPropertyValue = $this->getUnknownValueStr('bot_property');
        } else {
            $botPropertyValue = $botProperty->value;
        }

        return $botPropertyValue;
    }
}
