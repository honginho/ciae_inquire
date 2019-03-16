<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StuFromPartnerSchool extends Model
{
    //
    use SoftDeletes;
    protected $table = 'stu_from_partner_school';
    
    protected $fillable = [
        'college', 'dept', 'name', 'stuLevel', 'nation',
        'startDate', 'endDate', 'comments'
    ];


    public $timestamps = false;
    protected $dates = ['deleted_at'];
}
