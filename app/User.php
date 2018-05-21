<?php

namespace App;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\QueryBuilder\QueryBuilder;
use Zend\Diactoros\Request;

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
        'email', 'password', 'fb', 'ig', 'line', 'role', 'image_path', 'supervisor_id'
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
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

//    public function supervisors()
//    {
//        if ($this->role === 'Subordinates'){
//            return $this->belongsToMany('App\User','supervisor_subordinate', 'supervisor_id');
//        }
//
//    }
//    public function subordinates()
//    {
//        if ($this->role === 'Supervisor'){
//            return $this->belongsToMany('App\User','supervisor_subordinate', 'supervisor_id');
//        }
//    }

    private function populateSupervisor($users){
        foreach($users as $user){
            try{
                $user->supervisor = User::findOrFail($user->supervisor_id);
            }
            catch(ModelNotFoundException $e)
            {
            }
        }
        return $users;
    }
    public function subordinates(){
        $users = $this->hasMany('App\User', 'supervisor_id', 'id');
        return $this->populateSupervisor($users);
    }
    public function supervisors(){
        $users =  $this->belongsTo('App\User','supervisor_id');
        return $this->populateSupervisor($users);
    }
    public function tasks(){
        if ($this->role == 'Supervisor') {
            $tasks = $this->hasMany('App\Task','assigner')->get();
        }
        else {
            $tasks = $this->hasMany('App\Task','assignee')->get();
        }
        foreach ($tasks as $task){
            $task->assigner = User::find($task->assigner);
            $task->assignee = User::find($task->assignee);
        }
        return $tasks;
    }
    public function leaves(){

        if ($this->role ==   'Supervisor') {
            $ids = array();
            foreach($this->subordinates()->get() as $usb){
                array_push($ids, $usb->id);
            }
//            return $ids;
            $leaves = DB::table('leaves')
                ->select(DB::raw('*'))
                ->whereIn('leaver_id', $ids)
                ->get();
        }
        else{
            $leaves =$this->hasMany('App\Leave','leaver_id')->get();
        }
        foreach ($leaves as $leave){
            $leave->leaver = User::find($leave->leaver_id);
            $leave->substitution = User::find($leave->substitution_id);
            $leave->task = Task::find($leave->task_id);
            $leave->task->assigner = User::find($leave->task->assigner);
            $leave->task->assignee = User::find($leave->task->assignee);
        }
        return $leaves;
    }
    public function substitution(){
        if($this->role == 'Supervisor'){
            $users = $this->hasMany('App\User', 'supervisor_id', 'id')->get();
            $allLeaves = array();
            foreach ($users as $user){
                $leaves = $user->hasMany('App\Leave','substitution_id')->whereIn('status', ['substituteApproved'])->get();
                foreach ($leaves as $leave){
                    $leave->leaver = User::find($leave->leaver_id);
                    $leave->substitution = User::find($leave->substitution_id);
                    $leave->task = Task::find($leave->task_id);
                    $leave->task->assigner = User::find($leave->task->assigner);
                    $leave->task->assignee = User::find($leave->task->assignee);
                }
                if(count($leaves) >0){
                    $allLeaves = $allLeaves + $leave->toArray();
                }

            }
            return $allLeaves;
        }
        $leaves = $this->hasMany('App\Leave','substitution_id')->whereIn('status', ['pending'])->get();
        foreach ($leaves as $leave){
            $leave->leaver = User::find($leave->leaver_id);
            $leave->substitution = User::find($leave->substitution_id);
            $leave->task = Task::find($leave->task_id);
            $leave->task->assigner = User::find($leave->task->assigner);
            $leave->task->assignee = User::find($leave->task->assignee);
        }
        return $leaves;
    }
}
