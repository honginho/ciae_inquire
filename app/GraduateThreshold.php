<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GraduateThreshold extends Model
{
    //
    use SoftDeletes;

    protected $table ='graduate_threshold';
    protected $fillable=['college','dept','testName',
    					'testGrade',
    					'comments'];
    public $timestamps=false;
    protected $dates = ['deleted_at'];
}
