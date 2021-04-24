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
use App\Models\BotRating;
use App\Models\ClientCategory;
use ProgramO\V3\Cacher;
use Illuminate\Support\Facades\Log;
use App\Tags\AimlTag;
use App\Models\Conversation;
use ProgramO\V3\DB;

class RateTag extends AimlTag
{
    protected $tagName = "Rate";

    /**
     * Rate Constructor.
     * @param Conversation $conversation
     * @param $attributes
     */
    public function __construct(Conversation $conversation, $attributes = [])
    {

        parent::__construct($conversation, $attributes);
    }


    /**
     * when we close the tag
     * just return send the response all the way up the tag stack
     * all the way up to the template tag
     *
     * @return string
     */
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

        if ($this->hasAttributes()) {
            if ($this->hasAttribute('NAME')) {
                $feature = $this->getAttribute('NAME');
                if ($feature == 'count') {
                    $val = $this->conversation->bot->botRatingCount();
                    if (empty($val)) {
                        $val = '0';
                    }
                    $this->buildResponse($val);
                } elseif ($feature == 'average') {
                    $val = $this->conversation->bot->botRatingAvg();
                    if (empty($val)) {
                        $val = 'unrated';
                    }
                    $this->buildResponse($val);
                } elseif ($feature == 'max') {
                    $val = $this->conversation->bot->botRatingMax();
                    if (empty($val)) {
                        $val = 'unrated';
                    }
                    $this->buildResponse($val);
                } elseif ($feature == 'min') {
                    $val = $this->conversation->bot->botRatingMin();
                    if (empty($val)) {
                        $val = 'unrated';
                    }
                    $this->buildResponse($val);
                }
            }
        } else {
            //this is a set rating...
            $contents = $this->getCurrentResponse(true);

            //we will only save numbers...
            if (is_numeric($contents)) {
                //reset if over the min or the max
                if ((float)$contents<=0) {
                    $contents=0;
                } elseif ((float)$contents>=5) {
                    $contents=5;
                }

                $rating = new BotRating();
                $rating->conversation_id=$this->conversation->id;
                $rating->bot_id=$this->conversation->bot->id;
                $rating->rating=$contents;
                $rating->save();
            }
        }
    }
}
