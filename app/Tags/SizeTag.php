<?php
namespace App\Tags;

use App\Classes\LemurLog;
use App\Models\Category;
use App\Models\Conversation;

/**
 * Class SizeTag
 * @package App\Tags
 * Documentation on this tag, examples and explanation
 * see: https://docs.lemurengine.com/aiml.html
 */
class SizeTag extends AimlTag
{
    protected $tagName = "Size";


    /**
     * SizeTag Constructor.
     * @param Conversation $conversation
     * @param $attributes
     */
    public function __construct(Conversation $conversation, $attributes = [])
    {

        parent::__construct($conversation, $attributes);
    }


    public function getTotalCategories()
    {

        $botId = $this->conversation->bot->id;

        $sqlBuilder = Category::select(
            'categories.id',
            'categories.slug',
            'categories.pattern',
            'categories.regexp_pattern',
            'categories.first_letter_pattern',
            'categories.topic',
            'categories.regexp_topic',
            'categories.first_letter_topic',
            'categories.that',
            'categories.regexp_that',
            'categories.first_letter_that',
            'categories.template'
        )
            ->join('category_groups', 'category_groups.id', '=', 'categories.category_group_id')
            ->join('bot_category_groups', function ($join) use ($botId) {
                $join->on('category_groups.id', '=', 'bot_category_groups.category_group_id')
                    ->where('bot_category_groups.bot_id', $botId);
            })
            ->where('categories.status', 'A')
            ->where('category_groups.status', 'A');

        return $sqlBuilder->count();
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
        $tagContents = $this->getTotalCategories();
        $this->buildResponse($tagContents);
    }
}
