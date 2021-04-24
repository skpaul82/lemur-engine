<?php

namespace App\Repositories;

use App\Models\Bot;
use App\Models\BotKey;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;

/**
 * Class BotKeyRepository
 * @package App\Repositories
 * @version April 4, 2021, 9:42 am UTC
*/

class BotKeyRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'bot_id',
        'user_id',
        'slug',
        'name',
        'description',
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
        return BotKey::class;
    }
}
