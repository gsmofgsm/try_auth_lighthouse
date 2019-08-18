<?php

namespace App\Policies;

use App\User;

class BasePolicy
{
    /** @var User $user */
    public $user;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->user = User::getMe();
    }
}
