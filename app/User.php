<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fname', 'lname', 'address', 'telno', 'department',
        'email', 'password', 'fb', 'ig', 'line', 'role'
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
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The storage format of the model's date columns.
     *
     * @var string
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */

    public function supervisors()
    {
        if ($this->role === 'Subordinates'){
            return $this->belongsToMany('App\User','supervisor_subordinate', 'supervisor_id');
        }

    }
    public function subordinates()
    {
        if ($this->role === 'Supervisor'){
            return $this->belongsToMany('App\User','supervisor_subordinate', 'supervisor_id');
        }
    }
}
