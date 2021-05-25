<?php
namespace App\Tags;

use App\Services\TalkService;
use App\Models\Conversation;

/**
 * Class RandomTag
 * @package App\Tags
 * Documentation on this tag, examples and explanation
 * see: https://docs.lemurengine.com/aiml.html
 */
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
