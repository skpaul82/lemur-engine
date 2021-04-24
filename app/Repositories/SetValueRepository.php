<?php

namespace App\Repositories;

use App\Models\Language;
use App\Models\Set;
use App\Models\SetValue;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;

/**
 * Class SetValueRepository
 * @package App\Repositories
 * @version January 6, 2021, 1:04 pm UTC
*/

class SetValueRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'set_id',
        'user_id',
        'slug',
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
        return SetValue::class;
    }
}
