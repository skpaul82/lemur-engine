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

class PatternTag extends AimlTag
{
    protected $tagName = "Pattern";


    /**
     * PatternTag Constructor.
     * @param Conversation $conversation
     * @param $attributes

    //this has been intentionally left empty
    //there are no pattern tests
    //why?
    //because the pattern tag is NOT parsed at run time
     */
    public function __construct(Conversation $conversation, $attributes = [])
    {

        parent::__construct($conversation, $attributes);
    }
}
