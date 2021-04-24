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
 * @AimlTagDescription Formats a string to upper lower case
 *
 */

namespace App\Tags;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Models\Conversation;

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
