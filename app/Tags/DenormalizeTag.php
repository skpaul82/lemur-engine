<?php
namespace App\Tags;

use App\Classes\LemurLog;
use App\Models\Normalization;
use App\Models\Conversation;

/**
 * Class DenormalizeTag
 * @package App\Tags
 * Documentation on this tag, examples and explanation
 * see: https://docs.lemurengine.com/aiml.html
 */
class DenormalizeTag extends AimlTag
{
    protected $tagName = "Denormalize";


    /**
     * DenormalizeTag Constructor.
     * @param Conversation $conversation
     * @param $attributes
     */
    public function __construct(Conversation $conversation, $attributes = [])
    {

        parent::__construct($conversation, $attributes);
    }


    /**
     * when we close the <set> tag we need to decide if we want
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

        $contents = $this->getCurrentTagContents(true);

        $languageId = $this->conversation->bot->language_id;

        $result = Normalization::where('normalized_value', $contents)->where('language_id', $languageId)->first();

        if ($result!=null) {
            $contents = $result->original_value;
        }

        $this->buildResponse($contents);
    }
}
