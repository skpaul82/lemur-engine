<?php

namespace App\Repositories;

use App\Models\Language;
use App\Models\WordSpellingGroup;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;

/**
 * Class WordSpellingGroupRepository
 * @package App\Repositories
 * @version January 6, 2021, 1:08 pm UTC
*/

class WordSpellingGroupRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'language_id',
        'user_id',
        'slug',
        'name',
        'description',
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
        return WordSpellingGroup::class;
    }
}
