<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransnationalDegree extends Model
{
    //
    use SoftDeletes;
    protected $table ='transnational_degree';
    protected $fillable=['college','dept','nation',
    					'chtName','engName','stuLevel','year'
    					,'classMode','degreeMode','comments'];
    public $timestamps=false;
    protected $dates = ['deleted_at'];
}
