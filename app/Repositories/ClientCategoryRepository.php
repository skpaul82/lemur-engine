<?php

namespace App\Repositories;

use App\Models\Bot;
use App\Models\Client;
use App\Models\ClientCategory;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * Class ClientCategoryRepository
 * @package App\Repositories
 * @version January 21, 2021, 7:55 am UTC
*/

class ClientCategoryRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'client_id',
        'bot_id',
        'turn_id',
        'slug',
        'pattern',
        'template',
        'tag'
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
        return ClientCategory::class;
    }
}
