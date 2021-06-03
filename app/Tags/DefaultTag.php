<?php
namespace App\Tags;

use App\Models\Conversation;

/**
 * Class DefaultTag
 * @package App\Tags
 * Documentation on this tag, examples and explanation
 * see: https://docs.lemurengine.com/aiml.html
 */
class DefaultTag extends AimlTag
{
    protected $tagName = "Default";


    /**
     * DefaultTag Constructor.
     * @param Conversation $conversation
     * @param $attributes
     */
    public function __construct(Conversation $conversation, $attributes = [])
    {


        parent::__construct($conversation, $attributes);
    }




    public function closeTag()
    {

        $this->buildResponse($this->conversation->bot->default_response);
    }
}
