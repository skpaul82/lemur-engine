<?php

namespace App\Repositories;

use App\Models\Map;
use App\Models\MapValue;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;

/**
 * Class MapValueRepository
 * @package App\Repositories
 * @version January 6, 2021, 1:02 pm UTC
*/

class MapValueRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'map_id',
        'user_id',
        'slug',
        'word',
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
        return MapValue::class;
    }
}
