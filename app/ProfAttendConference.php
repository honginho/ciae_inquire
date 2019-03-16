<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProfAttendConference extends Model
{
    //
    use SoftDeletes;
    protected $table ='prof_attend_conference';
    protected $fillable=['college','dept','name',
    					'profLevel','nation','confName',
                        'publishedPaperName','startDate',
                        'endDate','comments'];
    public $timestamps=false;
    protected $dates = ['deleted_at'];
}
?>