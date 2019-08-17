<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pharmacy extends Model
{
    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }
}
