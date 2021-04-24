<?php
/**
 * Created by PhpStorm.
 * User: liseperu
 * Date: 16/08/2016
 * Time: 17:51
 *
 *
 * @AimlTag Lowercase
 * @AimlVersion 1.0,2.0
 * @AimlTagDescription Formats a string to upper upper case
 *
 */

namespace App\Tags;

use Illuminate\Support\Facades\Log;
use App\Models\Conversation;

/**
 * Class Category
 * @package App\Tags
 *
 * this has been intentionally left empty
 * why?
 * because the category tag is NOT parsed at run time
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
