<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ForeignStu extends Model
{
    //
    use SoftDeletes;
    protected $table ='foreign_stu';
    protected $fillable=['college','dept','chtName',
    					'engName','stuID','stuLevel',
    					'nation','engNation','startDate',
    					'endDate','comments','status'];
    public $timestamps=false;
    protected $dates = ['deleted_at'];

}
