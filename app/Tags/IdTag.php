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
 * @AimlTagDescription Formats a string to upper lower case
 *
 */

namespace App\Tags;

use Illuminate\Support\Facades\Log;
use App\Models\Conversation;

class IdTag extends AimlTag
{
    protected $tagName = "Id";


    /**
     * IdTag Constructor.
     * @param Conversation $conversation
     * @param $attributes
     */
    public function __construct(Conversation $conversation, $attributes = [])
    {

        parent::__construct($conversation, $attributes);
    }

    public function closeTag()
    {

        $conversationSlug = $this->conversation->slug;
        $this->buildResponse($conversationSlug);
    }
}
