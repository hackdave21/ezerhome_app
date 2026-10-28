<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Registered;

/** Tout nouveau compte reçoit le rôle « consultant » (cumulable avec d'autres rôles ensuite). */
class AssignDefaultRole
{
    public function handle(Registered $event): void
    {
        $user = $event->user;

        if (method_exists($user, 'assignRole') && ! $user->hasRole('consultant')) {
            $user->assignRole('consultant');
        }
    }
}
