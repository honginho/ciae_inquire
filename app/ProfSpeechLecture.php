<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProfSpeechLecture extends Model
{
    //
    use SoftDeletes;
    protected $table = 'prof_speech_lecture';
    protected $fillable = ['college','dept','name',
    					'profLevel','lecture','isForeign','place',
    					'startDate','endDate','comments'];
    public $timestamps = false;
    protected $dates = ['deleted_at'];
}
