<?php

namespace App\Repositories;

use App\Models\ConversationProperty;
use App\Repositories\BaseRepository;

/**
 * Class ConversationPropertyRepository
 * @package App\Repositories
 * @version January 6, 2021, 12:59 pm UTC
*/

class ConversationPropertyRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'conversation_id',
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
        return ConversationProperty::class;
    }
}
