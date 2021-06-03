<?php
namespace App\Tags;

use App\Models\Conversation;

/**
 * Class Category
 * @package App\Tags
 * Documentation on this tag, examples and explanation
 * see: https://docs.lemurengine.com/aiml.html
 *
 * this has been intentionally left empty
 * why? because the category tag is NOT parsed at run time
 */
class CategoryTag extends AimlTag
{
    protected $tagName = "Category";


    /**
     * CategoryTag Constructor.
     * @param Conversation $conversation
     * @param $attributes
     */
    public function __construct(Conversation $conversation, $attributes = [])
    {

        parent::__construct($conversation, $attributes);
    }
}
