<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AttendInternationalOrganization extends Model
{
	use SoftDeletes;
    protected $table ='attend_international_organization';
    protected $fillable=['college','dept','name',
    					'organization','startDate',
    					'endDate','comments'];
                        
    public $timestamps=false;
    protected $dates = ['deleted_at'];
}
