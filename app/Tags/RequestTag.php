<?php
namespace App\Tags;

use App\Models\Conversation;
use App\Models\Turn;

/**
 * Class RequestTag
 * @package App\Tags
 * Documentation on this tag, examples and explanation
 * see: https://docs.lemurengine.com/aiml.html
 */
class RequestTag extends AimlTag
{
    protected $tagName = "Request";

    /**
     * Request Constructor.
     * @param Conversation $conversation
     * @param $attributes
     */
    public function __construct(Conversation $conversation, $attributes = [])
    {

        parent::__construct($conversation, $attributes);
    }

    /**
     * @return string|void
     */
    public function closeTag()
    {


        //get the index in question and if there is none set it to the default which is 1
        if ($this->hasAttribute('INDEX')) {
            $index = $this->getAttribute('INDEX');
        } else {
            //0 is the current conversation
            $index = 1;
        }

        //For offset purposes 1=0, 2=1 etc so decremement the index by 1 for the offset
        $offset = $index-1;

        $turn = Turn::where('conversation_id', $this->conversation->id)
            ->where('source', 'human')->latest('id')->skip($offset)->first();

        if ($turn===null) {
            $input = $this->getUnknownValueStr('input');
        } else {
            $input = $turn->input;
        }

        $this->buildResponse($input);
    }
}
