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
use App\Models\WordTransformation;
use Illuminate\Support\Facades\Log;
use App\Models\Conversation;
use ProgramO\V3\DB;

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

        $contents = $this->getCurrentResponse(true);
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
