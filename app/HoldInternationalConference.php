<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HoldInternationalConference extends Model
{
    use SoftDeletes;
    protected $table = 'hold_international_conference';
    protected $fillable = ['academicYear','college','dept','holdWayId',
    					'holdWay','confName','confHoldNationId',
    					'startDate','endDate','comments'];
    public $timestamps = false;
    protected $dates = ['deleted_at'];
}
