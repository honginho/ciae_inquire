<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CooperationProj extends Model
{
    //
    use SoftDeletes;
    protected $table ='cooperation_proj';
    protected $fillable=['college','dept','name',
    					'projName','projOrg','projContent',
    					'startDate','endDate','comments'
    					];
    public $timestamps=false;
    protected $dates = ['deleted_at'];
}
