<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CollegeData extends Model
{
    //
    use SoftDeletes;
    protected $table = 'college_data';

    protected $fillable = [
        'college', 'dept', 'chtName',
    ];

	public static function toChtName($college, $dept){
        return CollegeData::where('college',$college)
                          ->where('dept',$dept)->first();
    }
    
    public $timestamps = false;
    protected $dates = ['deleted_at'];
}
