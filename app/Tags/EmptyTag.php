<?php
namespace App\Tags;

use App\Classes\LemurLog;
use App\Models\EmptyResponse;
use App\Models\Conversation;

/**
 * Class EmptyTag
 * @package App\Tags
 * Documentation on this tag, examples and explanation
 * see: https://docs.lemurengine.com/aiml.html
 */
class EmptyTag extends AimlTag
{
    protected $tagName = "Empty";


    /**
     * EmptyTag Constructor.
     * @param Conversation $conversation
     * @param $attributes
     * Saves the contents of this tag to the empty_responses table
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

        $input = $this->getCurrentTagContents();

        $empty = EmptyResponse::where('bot_id', $this->conversation->bot->id)->where('input', 'like', $input)->first();

        if ($empty===null) {
            $emptyResponse = new EmptyResponse();
            $emptyResponse->bot_id = $this->conversation->bot->id;
            $emptyResponse->input = $input;
            $emptyResponse->occurrences = 1;
            $emptyResponse->save();
        } else {
            $empty->occurrences++;
            $empty->save();
        }
    }
}
