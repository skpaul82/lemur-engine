<?php

namespace App\Repositories;

use App\Models\Turn;
use App\Repositories\BaseRepository;

/**
 * Class TurnRepository
 * @package App\Repositories
 * @version January 13, 2021, 2:03 pm UTC
*/

class TurnRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'conversation_id',
        'client_category_id',
        'category_id',
        'parent_turn_id',
        'slug',
        'input',
        'output',
        'status',
        'source',
        'is_display',
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
        return Turn::class;
    }
}
