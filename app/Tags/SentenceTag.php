<?php
namespace App\Tags;

use App\Classes\LemurLog;
use App\Classes\LemurStr;
use App\Models\Conversation;

/**
 * Class SentenceTag
 * @package App\Tags
 * Documentation on this tag, examples and explanation
 * see: https://docs.lemurengine.com/aiml.html
 */
class SentenceTag extends AimlTag
{
    protected $tagName = "Sentence";


    /**
     * SentenceTag Constructor.
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
        $formattedContents = $this->formatSentence($contents);
        $this->buildResponse($formattedContents);
    }


    public function formatSentence($string)
    {


        $sentences = preg_split('/([.?!]+)/', $string, -1, PREG_SPLIT_NO_EMPTY|PREG_SPLIT_DELIM_CAPTURE);
        $new_string = '';
        foreach ($sentences as $key => $sentence) {
            $new_string .= ($key & 1) == 0?
                LemurStr::mbUcfirst(trim($sentence)) :
                $sentence.' ';
        }

        return trim($new_string);
    }
}
