<?php

namespace App\Repositories;

use App\Models\Language;
use App\Models\WordSpelling;
use App\Models\WordSpellingGroup;
use App\Repositories\BaseRepository;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * Class WordSpellingRepository
 * @package App\Repositories
 * @version January 6, 2021, 1:08 pm UTC
*/

class WordSpellingRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_id',
        'word_spelling_group_id',
        'slug',
        'word',
        'replacement'
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
        return WordSpelling::class;
    }
}
