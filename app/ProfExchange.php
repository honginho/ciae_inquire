<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProfExchange extends Model
{
    //
    use SoftDeletes;
    protected $table ='prof_exchange';
    protected $fillable=['college','dept','name',
    					'profLevel','nation',
    					'startDate','endDate','comments'];
    public $timestamps=false;
    protected $dates = ['deleted_at'];
}
