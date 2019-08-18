<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static $my_pharmacy_ids = [1];

    public static $my_abilities = ['address'];

    public static $my_roles = [
        [
            'permissions' => [
                ['name' => 'address']
            ]
        ]
    ];

    public $roles;
    public $pharmacy_ids;

    public static function setMyAbilities($abilities){
        static::$my_abilities = $abilities;
    }

    public static function setPharmacyIds($ids) {
        static::$my_pharmacy_ids = $ids;
    }

    public static function getMe(){
        $me = new static();
        $permissions = [];
        foreach (static::$my_abilities as $ability) {
            $permissions[] = ['name' => $ability];
        }
        $me->roles = [
            ['permissions' => $permissions]
        ];
        $me->pharmacy_ids = static::$my_pharmacy_ids;
        return $me;
    }

    public function can($query, $pharmacy_id=null)
    {
        if(! is_null($pharmacy_id)) {
            return in_array($pharmacy_id, $this->pharmacy_ids);
        }

        foreach ($this->roles as $role) {
            foreach ($role['permissions'] as $permission) {
                if ($permission['name'] === $query) {
                    return true;
                }
            }
        }
        return false;
    }
}
