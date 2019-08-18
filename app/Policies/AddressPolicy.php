<?php

namespace App\Policies;

use App\Address;
use App\Pharmacy;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AddressPolicy extends BasePolicy
{
    use HandlesAuthorization;

    public function address(?User $user, Address $address)
    {
        $pharmacy_id = $address->pharmacy->id;
        return $this->user->can('address', $pharmacy_id);
    }
}
