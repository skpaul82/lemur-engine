<?php

namespace App\Repositories;

use App\Models\Bot;
use App\Models\BotCategoryGroup;
use App\Models\CategoryGroup;
use App\Models\Language;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * Class BotCategoryGroupRepository
 * @package App\Repositories
 * @version January 6, 2021, 12:50 pm UTC
*/

class BotCategoryGroupRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_id',
        'bot_id',
        'category_group_id',
        'slug',
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
        return BotCategoryGroup::class;
    }

    /**
     * Add extra data before saving
     *
     * @param array $input
     *
     * @return Model
     */
    public function create($input)
    {
        //so lets update or create a new BotCategoryGroup
        $item = BotCategoryGroup::withTrashed()->updateOrCreate([
            'bot_id' => $input['bot_id'],
            'category_group_id' => $input['category_group_id']
        ]);

        //if deleted lets restore
        if (!is_null($item->deleted_at)) {
            $item->restore();
        }

        return $item;
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

        //resolve the bot to an id
        $totalCatGroups = count($input['category_group_id']);


        for ($i=0; $i<$totalCatGroups; $i++) {
            //find the category group
            $categoryGroup = CategoryGroup::where('slug', $input['category_group_id'][$i])->first();

            //
            $linked = $input['linked'][$i];

            //we want to link this bot to this category group
            if ($linked == 1) {
                //so lets update or create a new BotCategoryGroup
                $item = BotCategoryGroup::withTrashed()->updateOrCreate([
                    'bot_id' => $input['bot_id'],
                    'category_group_id' => $categoryGroup->id
                ]);

                //if deleted lets restore
                if (!is_null($item->deleted_at)) {
                    $item->restore();
                }
            } else {
                //if the linked group exists then delete it
                BotCategoryGroup::where('bot_id', $input['bot_id'])
                    ->where('category_group_id', $categoryGroup->id)
                    ->delete();
            }
        }

        return true;
    }
    /**
     * Add extra data before saving
     *
     * @param array $input
     *
     * @return Model
     */
    public function createOrUpdate($input, $id = null)
    {



            //we want to link this bot to this category group
        if (!is_null($id)) {
            //so lets update/restore or create a new BotCategoryGroup
            $item = BotCategoryGroup::withTrashed()->updateOrCreate([
                'bot_id' => $input['bot_id'],
                'category_group_id' => $input['category_group_id']
            ])->where('id', $id);
        } else {
            //so lets update or create a new BotCategoryGroup
            $item = BotCategoryGroup::withTrashed()->updateOrCreate([
                'bot_id' => $input['bot_id'],
                'category_group_id' => $input['category_group_id']
            ]);
        }

        //if deleted lets restore
        if (!is_null($item->deleted_at)) {
            $item->restore();
        }
    }
}
