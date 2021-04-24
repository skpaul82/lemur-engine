<?php

namespace App\Repositories;

use App\Models\CategoryGroup;
use App\Models\Language;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;

/**
 * Class CategoryGroupRepository
 * @package App\Repositories
 * @version January 7, 2021, 8:10 am UTC
*/

class CategoryGroupRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_id',
        'language_id',
        'slug',
        'name',
        'description',
        'status',
        'is_master'
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
        return CategoryGroup::class;
    }
}
