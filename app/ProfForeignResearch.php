<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProfForeignResearch extends Model
{
    //
    use SoftDeletes;
    protected $table ='prof_foreign_research';
    protected $fillable=['college','dept','name',
    					'profLevel','nation',
    					'startDate','endDate','comments'];
    public $timestamps=false;
    protected $dates = ['deleted_at'];
}
