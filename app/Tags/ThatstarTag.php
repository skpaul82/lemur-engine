<?php
namespace App\Tags;

use App\Models\Wildcard;
use App\Models\Conversation;

/**
 * Class ThatstarTag
 * @package App\Tags
 * Documentation on this tag, examples and explanation
 * see: https://docs.lemurengine.com/aiml.html
 */
class ThatstarTag extends AimlTag
{
    protected $tagName = "Thatstar";

    /**
     * ThatstarTag Constructor.
     * @param Conversation $conversation
     * @param $attributes
     */
    public function __construct(Conversation $conversation, $attributes = [])
    {

        parent::__construct($conversation, $attributes);
    }

    public function closeTag()
    {

        //get the index in question and if there is none set it to the default which is 1
        if ($this->hasAttribute('INDEX')) {
            $index = $this->getAttribute('INDEX');
        } else {
            $index = 1;
        }

        //For offset purposes 1=0, 2=1 etc so decrement the index by 1 for the offset
        $offset = $index-1;

        $star = Wildcard::where('conversation_id', $this->conversation->id)
            ->where('type', 'thatstar')->latest('id')->skip($offset)->first();

        if ($star===null) {
            $value = $this->getUnknownValueStr('star');
        } else {
            $value = $star->value;
        }

        $this->buildResponse($value);
    }
}
