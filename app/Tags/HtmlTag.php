<?php
namespace App\Tags;

use App\Classes\LemurLog;
use App\Models\Conversation;
use SimpleXMLElement;

/**
 * Class HtmlTag
 * @package App\Tags
 * Documentation on this tag, examples and explanation
 * see: https://docs.lemurengine.com/aiml.html
 */
class HtmlTag extends AimlTag
{
    protected $tagName;
    protected $tagType;
    protected $allowHtml;

    /**
     * HtmlTag Constructor.
     * @param Conversation $conversation
     * @param array $attributes
     */
    public function __construct(Conversation $conversation, $attributes = [])
    {

        parent::__construct($conversation, $attributes);

        $this->allowHtml = $conversation->getAllowHtml();
    }



    /**
     * @param $tagName
     */
    public function setTagName($tagName)
    {
        $this->tagName = $tagName;
    }

    /**
     * @param $tagType
     */
    public function setTagType($tagType)
    {
        $this->tagType = $tagType;
    }

    /**
     * We have encountered a closing html tag
     * Process the contents
     *
     * @return string|void
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
    }

    public function processContents($contents)
    {



        $aimlTag = mb_strtolower($this->tagName);
        //dd($contents,$this->tagName,$this->getAttributes());


        if (!empty($this->allowHtml)) {
            if ($aimlTag!='') {
                if ($this->tagType=='wrapped') {
                    $contentsWithTags = <<<XML
<{$aimlTag}>$contents</{$aimlTag}>
XML;


                    //convert to xml
                    $aiml = new SimpleXMLElement($contentsWithTags);
                    //so we can add the tags in if needs be
                    if ($this->attributes) {
                        foreach ($this->attributes as $index => $value) {
                            $aiml->addAttribute(strtolower($index), $value);
                        }
                    }

                    $contents = trim(preg_replace('#<\?xml.*\?>#', '', $aiml->asXML()));
                } else {
                    $contents .= "<{$aimlTag}/>";
                }
            }
        }

        $this->buildResponse($contents);
    }
}
