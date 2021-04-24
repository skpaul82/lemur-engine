<?php

namespace App\Repositories;

use App\Models\EmptyResponse;
use App\Repositories\BaseRepository;

/**
 * Class EmptyResponseRepository
 * @package App\Repositories
 * @version January 6, 2021, 1:01 pm UTC
*/

class EmptyResponseRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'bot_id',
        'slug',
        'input',
        'occurrences'
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
        return EmptyResponse::class;
    }
}
