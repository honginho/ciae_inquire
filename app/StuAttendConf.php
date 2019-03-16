<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StuAttendConf extends Model
{
    //
    use SoftDeletes;
    protected $table ='stu_attend_conf';
    protected $fillable=['college','dept','name',
    					'stuLevel','nation','confName',
    					'startDate','endDate','comments'];
    public $timestamps=false;
    protected $dates = ['deleted_at'];


}
