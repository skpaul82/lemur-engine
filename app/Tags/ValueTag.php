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
use Illuminate\Support\Facades\Log;
use App\Tags\AimlTag;
use App\Models\Conversation;

class ValueTag extends AimlTag
{
    protected $tagName = "Value";

    /**
     * ValueTag Constructor.
     * @param Conversation $conversation
     * @param $attributes
     */
    public function __construct(Conversation $conversation, $attributes = false)
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
        //previously a tag such as <get> or <set> will have been called.....
        $start_time = microtime(true);
        $parentObject = $this->getPreviousTagObject();

        //we are inside the name tag therefore if the attribute does not have a key then we set it to name
        if (!isset($this->tagContents['VALUE'])&&(isset($this->tagContents[0]))) {
            $this->tagContents['VALUE']=$this->tagContents[0];
        }

        //there is a bug with this...
        //when using the <value></value> syntax the tag stack some how lost the value tag
        //i have 'fixed' this by just resolving out the tags into attributes in the cleanaiml function
        //$parentObject[$tagName]->processContents($this->_tagContents);
        $parentObject->setAttributes($this->tagContents);
        $parentObject->setAttributes($this->tagContents);


        $this->tagContents=array();
    }
}
