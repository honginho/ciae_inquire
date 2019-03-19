<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ForeignProfSpeechLecture extends Model
{
    use SoftDeletes;
    protected $table ='foreign_prof_speech_lecture';
    protected $fillable = ['college','dept','name',
                        'profLevel','nation','startDate',
                        'endDate','comments'];
    public $timestamps = false;
    protected $dates = ['deleted_at'];
}
?>