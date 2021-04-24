<?php

namespace App\Repositories;

use App\Models\Bot;
use App\Models\BotProperty;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;

/**
 * Class BotPropertyRepository
 * @package App\Repositories
 * @version January 6, 2021, 12:49 pm UTC
*/

class BotPropertyRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'bot_id',
        'user_id',
        'slug',
        'name',
        'value'
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return BotProperty::class;
    }

    /**
     * Update model record for given id
     *
     * @param array $input
     * @param int $id
     *
     * @return Model
     */
    public function update($input, $id)
    {

        //we never update the bot or the property name ...
        //we can only update the value
        $newInput['value']=$input['value'];

        return parent::update($input, $id);
    }


    /**
     * Add extra data before saving
     *
     * @param array $input
     *
     * @return Model
     */
    public function bulkCreate($input)
    {

        //firstly we will just blank all existing....
        BotProperty::where('bot_id', $input['bot_id'])->update(['value'=>'']);

        foreach ($input['name'] as $name => $value) {
            if ($value!='') {
                $botProperty = BotProperty::where('bot_id', $input['bot_id'])->where('name', $name)
                    ->withTrashed()
                    ->first();

                if ($botProperty==null) {
                    $botProperty = new BotProperty(['bot_id' => $input['bot_id'],
                        'name' => $name,
                        'value' => $value]);
                    $botProperty->save();
                } else {
                    $botProperty->value = $value;
                    $botProperty->deleted_at = null;
                    $botProperty->save();
                }
            }
        }

        return true;
    }
}
