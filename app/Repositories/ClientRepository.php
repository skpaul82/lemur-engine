<?php

namespace App\Repositories;

use App\Models\Bot;
use App\Models\Client;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * Class ClientRepository
 * @package App\Repositories
 * @version January 6, 2021, 12:56 pm UTC
*/

class ClientRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'bot_id',
        'slug',
        'is_banned'
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
        return Client::class;
    }

    /**
     * Update model record for given id
     *
     * @param array $input
     * @param int $id
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|Model
     */
    public function update($input, $id)
    {
        //You can only edit the is_banned field so unset everything else
        if (isset($input['is_banned'])) {
            $newInput['is_banned'] = $input['is_banned'];
        } else {
            $newInput['is_banned'] = 0;
        }

        return parent::update($newInput, $id);
    }
}
