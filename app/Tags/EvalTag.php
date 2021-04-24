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

class EvalTag extends EvaluateTag
{
    protected $tagName = "Eval";
}
