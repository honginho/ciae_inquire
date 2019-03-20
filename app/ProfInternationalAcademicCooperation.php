<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProfInternationalAcademicCooperation extends Model
{
    use SoftDeletes;
    protected $table ='prof_international_academic_cooperation';
    protected $fillable = ['college','dept','name',
    					'profLevel','partnerNation','projectName',
                        'startDate','endDate','money','comments'];
    public $timestamps = false;
    protected $dates = ['deleted_at'];
}
?>