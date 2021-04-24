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
use App\Models\Turn;
use Illuminate\Support\Facades\Log;
use App\Models\Conversation;

/**
 * Class That
 * @package App\Tags
 *
 * <that/> = <that index="1,1"/> - the last sentence the bot uttered.
 * <that index="1,2"/> - the 2nd to last sentence in <response index="1"/>, provided it exists.
 * <that index="2,1"/> - The last sentence of <response index="2"/>.
 *
 */


class ThatTag extends AimlTag
{
    protected $tagName = "That";

    /**
     * ThatTag Constructor.
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
            $index = 1;
        }

        $position = explode(",", $index);

        if (!isset($position[1])) {
            $position[1] = 1;
        }


        //For offset purposes 1=0, 2=1 etc so decrememnt the index by 1 for the offset
        $offset = $position[0];
        //as we using an array we can consider position 1 to be index 0 in the array
        $sentencePosition = $position[1]-1;

        //this is a v lazy way of doing this
        $turn = Turn::where('conversation_id', $this->conversation->id)
            ->where('source', 'human')->latest('id')->skip($offset)->first();

        if ($turn!==null) {
            $allTurnSentences = LemurStr::splitIntoSentences($turn->output);
            //now flip it as the last sentence = 1 (in AIML world)
            $allTurnSentences = array_reverse($allTurnSentences);
        }

        if (!isset($allTurnSentences[$sentencePosition])) {
            $that = $this->getUnknownValueStr('response');
        } else {
            $that = trim($allTurnSentences[$sentencePosition]);
        }

        $this->buildResponse($that);
    }
}
