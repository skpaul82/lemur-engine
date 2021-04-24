<?php
/**
 * Created by PhpStorm.
 * User: maczilla
 * Date: 08/04/16
 * Time: 17:06
 *
 * When a random tag is encounted it is assumed that it will contain <li>options</li> inside
 * This class will create a randomly named array upon option
 * store the encounted <li>values</li>
 * and select an item when closed
 *
 *
 *
 */
namespace App\Tags;

use Illuminate\Support\Facades\Log;
use App\Models\Conversation;

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
