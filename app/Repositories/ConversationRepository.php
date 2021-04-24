<?php

namespace App\Repositories;

use App\Models\Bot;
use App\Models\Conversation;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * Class ConversationRepository
 * @package App\Repositories
 * @version January 6, 2021, 12:58 pm UTC
*/

class ConversationRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'slug',
        'bot_id',
        'client_id',
        'allow_html'
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
        return Conversation::class;
    }
}
