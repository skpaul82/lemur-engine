<?php
/**
 * Created by PhpStorm.
 * User: liseperu
 * Date: 16/08/2016
 * Time: 17:51
 *
 *
 * @AimlTag Lowercase
 * @AimlVersion 1.0,2.0
 * @AimlTagDescription Formats a string to upper upper case
 *
 */

namespace App\Tags;

use App\Classes\LemurLog;
use Illuminate\Support\Facades\Log;
use App\Models\Conversation;

class UppercaseTag extends AimlTag
{
    protected $tagName = "Uppercase";


    /**
     * UppercaseTag Constructor.
     * @param Conversation $conversation
     * @param $attributes
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

        $contents = $this->getCurrentTagContents(true);
        $tagContents=mb_strtoupper($contents);
        $this->buildResponse($tagContents);
    }
}
