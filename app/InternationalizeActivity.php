<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InternationalizeActivity extends Model
{
    //
    use SoftDeletes;
    protected $table ='internationalize_activity';
    protected $fillable=['college','dept','activityName',
    					'place','host','guest',
    					'startDate','endDate','comments'];
    public $timestamps=false;
    protected $dates = ['deleted_at'];
}
