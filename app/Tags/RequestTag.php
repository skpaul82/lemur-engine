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

use App\Models\Conversation;
use App\Models\Turn;

/**
 * Class Request
 * @package App\Tags
 *
 * <request/> - the current request (all sentences in that request)
 * <request index="2"/> - the previous requests (all sentences in that request)
 * <request index="N"/> - the nth requests (all sentences in that request)
 * e.g. Hello. My name is Bob
 * Hello. My name is Bob = <request/>
 * 'unknown' = <request index="2"/>
 *
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
