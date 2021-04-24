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

use Illuminate\Support\Facades\Log;
use App\Tags\AimlTag;
use App\Models\Conversation;

class NameTag extends AimlTag
{
    protected $tagName = "Name";

    /**
     * NameTag Constructor.
     * @param Conversation $conversation
     * @param $attributes
     */
    public function __construct(Conversation $conversation, $attributes = [])
    {
        parent::__construct($conversation, $attributes);
    }



    public function closeTag()
    {

        //previously a tag such as <get> or <set> will have been called.....
        $previousObject = $this->getPreviousTagObject();
        $contents = $this->getCurrentResponse(true);
        //$parentObject[$tagName]->processContents($this->_tagContents);
        $previousObject->setAttributes(['NAME'=>$contents]);
        $previousObjectIndex = $this->getTagStack()->maxPosition()-1;
        $this->getTagStack()->overWrite($previousObject, $previousObjectIndex);
    }
}
