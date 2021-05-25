<?php
namespace App\Tags;

use App\Classes\LemurLog;
use App\Services\TalkService;
use App\Models\Conversation;

/**
 * Class SraiTag
 * @package App\Tags
 * Documentation on this tag, examples and explanation
 * see: https://docs.lemurengine.com/aiml.html
 */
class SraiTag extends AimlTag
{
    protected $tagName = "Srai";
    protected $talkService;
    protected $sraiCount;
    protected $maxSraiCount;

    /**
     * SraiTag Constructor.
     * @param TalkService $talkService
     * @param Conversation $conversation
     * @param $attributes
     */
    public function __construct(TalkService $talkService, Conversation $conversation, $attributes)
    {
        parent::__construct($conversation, $attributes);
        $this->talkService = $talkService;
        $this->sraiCount = $conversation->getVar('srai-count', 0);

        $this->maxSraiCount = $this->getMaxSraiCountFromConfig();
    }


    /**
     * @return string|void
     * @throws \Exception
     */
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

        $contents = $this->getCurrentTagContents(true);

        //check if we have reached the max levels of srai recursion
        //if so through an exception

        if ($this->isInLiTag()) { //if we are in a LI tag...
            $this->buildResponse("<srai>" . $contents . "</srai>");
        } else {
            $this->buildResponse($this->getResponseFromNewTalk($contents));
        }
    }


    public function getMaxSraiCountFromConfig()
    {

        return config('lemur_tag.recursion.max', 10);
    }

    public function checkMaxSraiReached()
    {

        $pid= $this->conversation->currentParentTurnId();

        $sraiCount = $this->conversation->countOpenSraiTags($pid);

        if ($sraiCount >= $this->maxSraiCount) { //if we have maxed out the recursions then we should exit
            return true;
        }

        return false;
    }



    public function getResponseFromNewTalk($contents)
    {

        $this->sraiCount++;

        if ($this->checkMaxSraiReached()) {
            //mark the turn as complete
            $this->conversation->completeTurn('E');
            return config('lemur_tag.recursion.message');
        }

        $this->conversation->setVar('srai-count', $this->sraiCount);

        $this->talkService->initFromTag($this->conversation, $contents, 'srai');

        $this->talkService->talk($contents);
        $response = $this->talkService->getOutput();
        $reParsedConversation = $this->talkService->getConversation();

        //set the debug
        $this->extractDebug($reParsedConversation);

        //copy local vars back for debugging
        //as we are still in the same turn carry all the vars over
        $vars = $reParsedConversation->getVars();
        foreach ($vars as $name => $value) {
            $this->conversation->setVar($name, $value);
        }
        return $response;
    }

    public function extractDebug($reParsedConversation)
    {

        //get all the debugArr and set it so we can see what happened
        //remember we only get the debug if in ui mode so this may be empty
        $output = $this->talkService->responseOutput($reParsedConversation);
        if (!empty($output['debugArr'])) {
            $this->conversation->setDebug('reparse', $output['debugArr']);
        }
    }
}
