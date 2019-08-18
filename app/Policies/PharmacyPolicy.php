<?php

namespace App\Policies;

use App\Pharmacy;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PharmacyPolicy extends BasePolicy
{
    public function pharmacy(?User $user, $pharmacy)
    {
        return $this->user->can('pharmacy', $pharmacy->id);
    }

    public function createAddress(?User $user, Pharmacy $pharmacy)
    {
        return $this->user->can('createAddress', $pharmacy->id);
    }
}
