<?php

namespace App\Repositories;

use App\Models\Bot;
use App\Models\Language;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;

/**
 * Class BotRepository
 * @package App\Repositories
 * @version January 6, 2021, 12:47 pm UTC
*/

class BotRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'slug',
        'language_id',
        'user_id',
        'name',
        'summary',
        'description',
        'default_response',
        'lemurtar_url',
        'image',
        'status',
        'is_master',
        'is_public'
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
        return Bot::class;
    }
}
