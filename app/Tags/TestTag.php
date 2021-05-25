<?php
namespace App\Tags;

use App\Models\Conversation;

/**
 * Class TestTag
 * @package App\Tags
 * Documentation on this tag, examples and explanation
 * see: https://docs.lemurengine.com/aiml.html
 */
class TestTag extends AimlTag
{
    protected $tagName = "Test";
    protected $someVar = "something";

    /**
     * this is purely used for testing so we can test the abstract AimlTag class
     *
     * @param Conversation $conversation
     * @param $attributes
     */

    public function __construct(Conversation $conversation, $attributes = [])
    {

        parent::__construct($conversation, $attributes);
    }
}
