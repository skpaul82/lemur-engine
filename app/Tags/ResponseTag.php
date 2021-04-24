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

use App\Models\Turn;
use Illuminate\Support\Facades\Log;
use App\Tags\AimlTag;
use App\Models\Conversation;

/**
 * Class Input
 * @package App\Tags
 *
 * <input index=“1”>
 *
 */


class ResponseTag extends AimlTag
{
    protected $tagName = "Response";

    /**
     * Response Constructor.
     * @param Conversation $conversation
     * @param $attributes

     * <response/> - the current response (all sentences in that response)
     * <response index="2"/> - the previous response (all sentences in that response)
     * <response index="N"/> - the nth response (all sentences in that response)
     * e.g. User: Hello. My name is Bob. Bot: Hi Bob. nice to meet you
     * Hi Bob. nice to meet you = <that/>
     * 'unknown' = <response index="2"/>
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
            $offset = $this->getAttribute('INDEX');
        } else {
            //1 is equal to the last response...
            $offset = 1;
        }



        $turn = Turn::where('conversation_id', $this->conversation->id)
            ->where('source', 'human')->latest('id')->skip($offset)->first();

        if ($turn===null) {
            $output = $this->getUnknownValueStr('response');
        } else {
            $output = $turn->output;
        }

        $this->buildResponse($output);
    }
}
