<?php

namespace App\Tags;

use App\Classes\LemurLog;
use Illuminate\Support\Facades\Log;
use App\Models\Conversation;

class TemplateTag extends AimlTag
{
    protected $tagName = "Template";

    public function __construct(Conversation $conversation, $attributes = [])
    {
        parent::__construct($conversation, $attributes);
    }



    public function closeTag()
    {

        //if we are in learning mode send the response back up the stack
        if ($this->isInLearningMode()) {
            //if we are in learning mode we will do something else instead of evaluating the contents
            //it will turn it back into aiml for saving...
            $contents = $this->getCurrentResponse(true);
            $contents = $this->buildAIMLIfInLearnMode($contents);

            $this->buildResponse($contents);
        } else {
            //if not this is the last tag ... just send the contents
            $contents = $this->getCurrentResponse(true);
          //  $this->buildResponse($contents);


            return $contents;
        }
    }
}
