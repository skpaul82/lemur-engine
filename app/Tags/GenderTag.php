<?php
namespace App\Tags;

use App\Classes\LemurLog;
use App\Models\WordTransformation;
use App\Models\Conversation;

/**
 * Class GenderTag
 * @package App\Tags
 * Documentation on this tag, examples and explanation
 * see: https://docs.lemurengine.com/aiml.html
 */
class GenderTag extends AimlTag
{
    protected $tagName = "Gender";


    /**
     * GenderTag Constructor.
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

        $preg = $this->getTransformations();

        $contents = $this->getCurrentTagContents(true);
        $words = explode(" ", $contents);

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

    public function getTransformations()
    {

        $transformations = WordTransformation::select(['third_person_form_female','third_person_form_male'])
            ->where('third_person_form_female', '!=', '')->where('third_person_form_male', '!=', '')->get();

        $preg = [];

        foreach ($transformations as $transform) {
            $preg['match'][]="/\b".$transform->third_person_form_female."\b/is";
            $preg['replace'][]=$transform->third_person_form_male;
            $preg['match'][]="/\b".$transform->third_person_form_male."\b/is";
            $preg['replace'][]=$transform->third_person_form_female;
        }

        return $preg;
    }
}
