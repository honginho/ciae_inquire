<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShortTermForeignStu extends Model
{
    //
    use SoftDeletes;
    protected $table= 'short_term_foreign_stu';

    protected $fillable=['college','dept','name',
    					'stuLevel','nation','startDate',
    					'endDate','comments'];
	public $timestamps=false;
	protected $dates = ['deleted_at'];  
}
