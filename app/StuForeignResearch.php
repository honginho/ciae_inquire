<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StuForeignResearch extends Model
{
    //
    use SoftDeletes;
    protected $table = 'stu_foreign_research';
    
    protected $fillable = [
        'college', 'dept', 'name', 'stuLevel', 'nation',
        'startDate', 'endDate', 'comments'
    ];


    public $timestamps = false;
    protected $dates = ['deleted_at'];
}
