<?php
namespace App\Tags;

use App\Models\Conversation;

/**
 * Class BeforethatTag
 * @package App\Tags
 * Documentation on this tag, examples and explanation
 * see: https://docs.lemurengine.com/aiml.html
 */
class BeforethatTag extends ThatTag
{
    protected $tagName = "Beforethat";

    /**
     * BeforethatTag Constructor.
     * Just here for backwards compatibility
     * @param Conversation $conversation
     * @param $attributes
     */
    public function __construct(Conversation $conversation, $attributes)
    {
        $attributes['index']='1';
        parent::__construct($conversation, $attributes);
    }
}
