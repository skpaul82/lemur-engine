<?php
namespace App\Tags;

use App\Services\TalkService;
use App\Models\Conversation;

/**
 * Class SrTag
 * @package App\Tags
 * Documentation on this tag, examples and explanation
 * see: https://docs.lemurengine.com/aiml.html
 */
class SrTag extends SraiTag
{
    protected $tagName = "Sr";

    /**
     * SrTag Constructor.
     * @param TalkService $talkService
     * @param Conversation $conversation
     * @param $attributes
     *
     * this will never get called as a method called expandSr($template){
     * expands the <sr/> tag to <srai><star/></srai>
     */
    public function __construct(TalkService $talkService, Conversation $conversation, $attributes)
    {
        parent::__construct($talkService, $conversation, $attributes);
    }
}
