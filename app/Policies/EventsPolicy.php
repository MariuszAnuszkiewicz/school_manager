<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EventsPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function index(User $user)
    {
        if (!$user->hasRole('pupil')) {
            return $this->deny("Sorry, you're not Pupil and you can't' see content.");
        }
        return true;
    }
}
