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

use App\Classes\AimlParser;
use App\Classes\LemurLog;
use App\Services\TalkService;
use Illuminate\Support\Facades\Log;
use App\Tags\AimlTag;
use App\Models\Conversation;

class RandomTag extends AimlTag
{



    /**
     * Random Constructor.
     * There isnt really anything to do here...
     * the random item are extracted as part of the aimlParser
     * @param TalkService $talkService
     * @param Conversation $conversation
     * @param $attributes
     */
    public function __construct(TalkService $talkService, Conversation $conversation, $attributes)
    {
        parent::__construct($conversation, $attributes);
    }
}
