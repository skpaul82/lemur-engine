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
use App\Classes\LemurStr;
use ProgramO\V3\DB;
use Illuminate\Support\Facades\Log;
use App\Tags\AimlTag;
use App\Models\Conversation;

class SetTag extends AimlTag
{
    protected $tagName = "Set";

    /**
     * SetTag Constructor.
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

        $contents = $this->getCurrentResponse(true);

        if ($this->hasAttributes() &&
                $this->checkIfParentAttributeValue('Li', 'LI_STATUS', 'true')) {
            //if this has a parent Li tag with a LI_STATUS = false then we cant set this...

            if ($this->hasAttribute('NAME')) {
                $name = $this->getAttribute('NAME');
                $this->conversation->setGlobalProperty($name, $contents);

                //in addition...
                if ($name == 'topic') {
                    //set directly in the db...
                    $this->conversation->setGlobalProperty('topic', $contents);
                }

                $this->buildResponse($contents);
            } elseif ($this->hasAttribute('VAR')) {
                $name = $this->getAttribute('VAR');
                $this->conversation->setVar($name, $contents);

                $this->buildResponse($contents);
            }
        } else {
            //todo
            LemurLog::warn(
                'Not setting',
                [
                    'conversation_id'=>$this->conversation->id,
                    'turn_id'=>$this->conversation->currentTurnId(),
                    'tag_id'=>$this->getTagId()
                ]
            );
        }
    }




    public function checkIfParentAttributeValue($tagName, $attribute, $value)
    {



        $previousObjectArr = $this->getPreviousTagByNames([$tagName]);

        if (!empty($previousObjectArr)) {
            $previousObject = $previousObjectArr['tag'];

            if ($previousObject->hasAttribute($attribute)) {
                return  $previousObject->checkAttribute($attribute, $value);
            }
        }

        return true;
    }
}
