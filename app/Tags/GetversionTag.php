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

use App\Services\TalkService;
use ProgramO\V3\Cacher;
use Illuminate\Support\Facades\Log;
use App\Tags\AimlTag;
use App\Models\Conversation;
use ProgramO\V3\AimlParser;
use ProgramO\V3\DB;

class GetversionTag extends VersionTag
{
    protected $tagName = "Getversion";

    /**
     * GetversionTag Constructor.
     * @param Conversation $conversation
     * @param $attributes
     */
    public function __construct(Conversation $conversation, $attributes)
    {
        parent::__construct($conversation,$attributes);
    }
}
