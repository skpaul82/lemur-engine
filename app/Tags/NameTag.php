<?php
namespace App\Tags;

use App\Models\Conversation;

/**
 * Class NameTag
 * @package App\Tags
 * Documentation on this tag, examples and explanation
 * see: https://docs.lemurengine.com/aiml.html
 */
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
        $contents = $this->getCurrentTagContents(true);
        //$parentObject[$tagName]->processContents($this->_tagContents);
        $previousObject->setAttributes(['NAME'=>$contents]);
        $previousObjectIndex = $this->getTagStack()->maxPosition()-1;
        $this->getTagStack()->overWrite($previousObject, $previousObjectIndex);
    }
}
