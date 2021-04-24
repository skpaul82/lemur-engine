<?php

namespace App\Policies;

use App\Models\Bot;
use App\Models\Turn;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Gate;

class TurnPolicy extends MasterPolicy
{
    use HandlesAuthorization;


    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //anyone can view a list of bots
        //the query will limit the users
        return Response::allow();
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Turn  $turn
     * @return mixed
     */
    public function view(User $user, Turn $turn)
    {
        //the $turn belongs to a conversation and that belongs to a bot and that bot belongs to a user
        //and if this bot is a master then anyone can view
        return $this->checkIfAdminOrOwner($user, $this->getBot($turn), true);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //admins can do this but they should really do this
        //this is an action which happens when the user is talking to the bot
        return $user->hasRole('admin')
            ? Response::allow()
            : Response::deny('You cannot perform this action.');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Turn  $turn
     * @return mixed
     */
    public function update(User $user, Turn $turn)
    {
        //the $turn belongs to a conversation and that belongs to a bot and that bot belongs to a user
        return $this->checkIfAdminOrOwner($user, $this->getBot($turn));
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Turn  $turn
     * @return mixed
     */
    public function delete(User $user, Turn $turn)
    {
        //the $turn belongs to a conversation and that belongs to a bot and that bot belongs to a user
        return $this->checkIfAdminOrOwner($user, $this->getBot($turn));
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Turn  $turn
     * @return mixed
     */
    public function restore(User $user, Turn $turn)
    {
        //the $turn belongs to a conversation and that belongs to a bot and that bot belongs to a user
        return $this->checkIfAdminOrOwner($user, $this->getBot($turn));
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Turn  $turn
     * @return mixed
     */
    public function forceDelete(User $user, Turn $turn)
    {
        //only admins can do this
        return $user->hasRole('admin')
            ? Response::allow()
            : Response::deny('You cannot perform this action.');
    }

    /**
     * @param $model
     * @return mixed
     */
    public function getBot($model)
    {
        //a property belongs to a conversation
        return Bot::find($model->conversation->bot_id);
    }
}
