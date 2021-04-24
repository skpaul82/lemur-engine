<?php
/**
 * Created by PhpStorm.
 * User: maczilla
 * Date: 08/04/16
 * Time: 17:06
 *
 * When a random tag is encounted it is assumed that it will contain <li>options</li> inside
 * This class will create a randomly named array upon option
 * store the encounted <li>values</li>
 * and select an item when closed
 *
 *
 *
 */
namespace App\Tags;

use App\Classes\LemurLog;
use App\Models\BotProperty;
use Illuminate\Support\Facades\Log;
use App\Models\Conversation;

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
