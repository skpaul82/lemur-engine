<?php
namespace App\Tags;

use App\Classes\LemurLog;
use App\Models\WordTransformation;
use App\Models\Conversation;

/**
 * Class Person2Tag
 * @package App\Tags
 * Documentation on this tag, examples and explanation
 * see: https://docs.lemurengine.com/aiml.html
 */
class Person2Tag extends AimlTag
{
    protected $tagName = "Person2";


    /**
     * Person2Tag Constructor.
     * @param Conversation $conversation
     * @param $attributes
     * which transforms pronouns between first and third person.
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
        $words = explode(" ", $contents);
        $preg = $this->getTransformations($words);

        if (empty($preg)) {
            $this->buildResponse($contents);
            return;
        }

        foreach ($words as $word) {
            $change = false;

            foreach ($preg['match'] as $index => $match) {
                $newWord = preg_replace($match, $preg['replace'][$index], $word);

                if ($newWord!=$word) {
                    $change=true;
                    $this->buildResponse($newWord);
                    break;
                }
            }

            if (!$change) {
                $this->buildResponse($word);
            }
        }
    }


    public function getTransformations($words)
    {

        $transformations = WordTransformation::select(['first_person_form','third_person_form'])
            ->whereIn('first_person_form', $words)->orWhereIn('third_person_form', $words)->get();
        $preg = [];

        foreach ($transformations as $transform) {
            $preg['match'][]="/\b".$transform->first_person_form."\b/is";
            $preg['replace'][]=$transform->third_person_form;
            $preg['match'][]="/\b".$transform->third_person_form."\b/is";
            $preg['replace'][]=$transform->first_person_form;
        }

        return $preg;
    }
}
