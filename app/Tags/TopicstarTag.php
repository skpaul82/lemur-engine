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
use App\Models\Wildcard;
use Illuminate\Support\Facades\Log;
use App\Tags\AimlTag;
use App\Models\Conversation;

/**
 * Class Topicstar
 * @package App\Tags
 *
 * <star index=“1”>
 */


class TopicstarTag extends AimlTag
{
    protected $tagName = "Topicstar";

    /**
     * TopicstarTag Constructor.
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
            ->where('type', 'topicstar')->latest('id')->skip($offset)->first();

        if ($star===null) {
            $value = $this->getUnknownValueStr('topicstar');
        } else {
            $value = $star->value;
        }

        $this->buildResponse($value);
    }
}
