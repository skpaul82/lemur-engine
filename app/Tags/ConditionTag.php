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
use App\Classes\LemurStr;
use App\Services\TalkService;
use ProgramO\V3\AimlParser;
use Illuminate\Support\Facades\Log;
use App\Tags\AimlTag;
use App\Models\Conversation;

class ConditionTag extends AimlTag
{

    protected $tagName = "Condition";
    protected $talkService;

    private $tmpContents = '';


    /**
     * ConditionTag Constructor.
     * @param Conversation $conversation
     * @param $attributes
     * @param TalkService $talkService
     */
    public function __construct(TalkService $talkService, Conversation $conversation, $attributes)
    {
        parent::__construct($conversation, $attributes);
        $this->talkService = $talkService;
    }

    public function getTmpContents()
    {
        return $this->tmpContents;
    }

    public function setTmpContents($tmpContents)
    {
        if ($this->checkAttribute('TYPE', 'LOOP')) {
            //we are in a loop... so we append the answer
            $this->tmpContents .= ' '.$tmpContents;
        } else {
            $this->tmpContents = $tmpContents;
        }
    }



    public function hasTmpContents()
    {
        return ($this->tmpContents!=''?true:false);
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


        //if this was a loop then we have already built the values by looping around...
        if ($this->checkAttribute('TYPE', 'LOOP')) {
            $tagContents = $this->getResponseFromReParse($this->tmpContents);
            $this->buildResponse($tagContents);
        } else {
            $tagContents = $this->getResponseFromReParse($this->tmpContents);
            $this->buildResponse($tagContents);
        }
    }
}
