<?php

namespace App\Policies;

use App\Models\Agenda;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AgendaPolicy
{
    use HandlesAuthorization;

    public function before(User $user)
    {
        if ($user->is_admin == 1) {
            return true;
        }
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Agenda  $agenda
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Agenda $agenda)
    {
        return $user->id == $agenda->user_id;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Agenda  $agenda
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Agenda $agenda)
    {
        return $user->id === $agenda->user_id;
    }

    public function delete(User $user, Agenda $agenda)
    {
        return $user->id == $agenda->user_id;
    }
}
