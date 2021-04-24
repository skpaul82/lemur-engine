<?php

namespace App\Repositories;

use App\Classes\LemurStr;
use App\Models\Category;
use App\Models\CategoryGroup;
use App\Models\Language;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;

/**
 * Class CategoryRepository
 * @package App\Repositories
 * @version January 7, 2021, 5:47 pm UTC
*/

class CategoryRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_id',
        'category_group_id',
        'slug',
        'pattern',
        'regexp_pattern',
        'first_letter_pattern',
        'topic',
        'regexp_topic',
        'first_letter_topic',
        'that',
        'regexp_that',
        'first_letter_that',
        'template',
        'status'
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
        return Category::class;
    }
}
