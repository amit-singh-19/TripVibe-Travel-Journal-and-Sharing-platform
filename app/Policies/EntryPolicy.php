<?php

namespace App\Policies;

use App\Models\Entry;
use App\Models\Journal;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EntryPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user, Journal $journal)
    {
        return $journal->user_id === $user->id || $journal->is_public;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Entry $entry)
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Entry $entry)
    {
        return $user->id === $entry->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Entry $entry)
    {
        return $user->id === $entry->user_id;
    }
} 