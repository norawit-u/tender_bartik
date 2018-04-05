<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'start', 'end', 'type', 'status','leaver_id','substitution_id','task_id','note'
    ];
    public $timestamps = true;

}
