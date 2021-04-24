<?php

namespace App\Repositories;

use App\Models\Language;
use App\Models\WordSpelling;
use App\Models\WordSpellingGroup;
use App\Models\WordTransformation;
use App\Repositories\BaseRepository;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;

/**
 * Class WordTransformationRepository
 * @package App\Repositories
 * @version January 6, 2021, 1:09 pm UTC
*/

class WordTransformationRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_id',
        'language_id',
        'slug',
        'first_person_form',
        'second_person_form',
        'third_person_form',
        'third_person_form_female',
        'third_person_form_male',
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
        return WordTransformation::class;
    }
}
