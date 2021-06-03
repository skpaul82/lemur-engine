<?php
namespace App\Tags;

use Carbon\Carbon;
use App\Models\Conversation;

/**
 * Class DateTag
 * @package App\Tags
 * Documentation on this tag, examples and explanation
 * see: https://docs.lemurengine.com/aiml.html
 */
class DateTag extends AimlTag
{
    protected $tagName = "Date";


    /**
     * DateTag Constructor.
     * @param Conversation $conversation
     * @param $attributes
     */
    public function __construct(Conversation $conversation, $attributes = [])
    {


        parent::__construct($conversation, $attributes);

        //if there is no format set then set default format
        if (empty($attributes['FORMAT'])) {
            $this->setAttributes(['FORMAT'=>"%B %d %Y"]);
        }

        if (empty($attributes['LOCALE'])) {
            $this->setAttributes(['LOCALE'=>"en_US"]);
        }

        if (empty($attributes['TIMEZONE'])) {
            $this->setAttributes(['TIMEZONE'=>""]);
        }
    }




    public function closeTag()
    {

        $date = Carbon::now()->locale($this->getAttribute('LOCALE'));

        if (!empty($attributes['TIMEZONE'])) {
            $date->timezone($this->getAttribute('TIMEZONE'));
        }

        $tagContents = $date->formatLocalized($this->getAttribute('FORMAT'));

        $this->buildResponse($tagContents);
    }
}
