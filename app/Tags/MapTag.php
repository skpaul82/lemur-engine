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

use App\Classes\LemurLog;
use App\Models\Map;
use App\Models\MapValue;
use Illuminate\Support\Facades\Log;
use App\Models\Conversation;

class MapTag extends AimlTag
{
    protected $tagName = "Map";


    /**
     * MapTag Constructor.
     * @param Conversation $conversation
     * @param $attributes
     */
    public function __construct(Conversation $conversation, $attributes = [])
    {

        parent::__construct($conversation, $attributes);
    }


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
        $mapName = $this->getAttribute('NAME');



        //this is cus we are setting a map...
        //we set it in the global conversation properties
        if ($this->hasAttribute('NAME')&& $this->hasAttribute('VALUE')) {
            $mapWord = $this->getAttribute('VALUE');

            $this->conversation->setGlobalProperty(strtolower('map.'.$mapName.'.'.$contents), $mapWord);
        } elseif ($this->hasAttribute('NAME')) {
            //first lets see if we have the custom map
            //which matches this request in a custom map in the global conversation properties
            $newContents = $this->conversation->getGlobalProperty(
                strtolower('map.'.$mapName.'.'.$contents),
                'no_map_found'
            );


            if ($newContents=='no_map_found') {

                $map = $this->getListOfAllowedMaps($mapName);

                if ($map!=null) {
                    $mapValue = MapValue::where('map_id', $map->id)->where('word', $contents)->first();
                    if ($mapValue!=null) {
                        $newContents = $mapValue->value;
                    }
                }
            }




            $this->buildResponse($newContents);
        }
    }


    public function getListOfAllowedMaps($mapName){

        //the maps has a user id
        $mapUserId = $this->conversation->bot->user_id;

        return Map::where('name',$mapName)
            ->where(function ($query)  use($mapUserId) {
                //it has to be owned by the bot author or be a master record
                $query->where('maps.user_id',$mapUserId)
                    ->orWhere('maps.is_master', 1);
            })->first();

    }
}
