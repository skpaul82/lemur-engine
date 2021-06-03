<?php
namespace App\Tags;

use App\Models\Conversation;

/**
 * Class ProgramTag
 * @package App\Tags
 * Documentation on this tag, examples and explanation
 * see: https://docs.lemurengine.com/aiml.html
 */
class ProgramTag extends VersionTag
{
    protected $tagName = "Program";


    /**
     * Program Constructor.
     * @param Conversation $conversation
     * @param $attributes
     */
    public function __construct(Conversation $conversation, $attributes = [])
    {
        parent::__construct($conversation, $attributes);
    }
}
