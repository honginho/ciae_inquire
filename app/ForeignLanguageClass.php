<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ForeignLanguageClass extends Model
{
    //
    use SoftDeletes;
    protected $table = 'foreign_language_class';

    protected $fillable = ['college','dept','year','semester','chtName','engName',
    						'teacher','language','totalCount','nationalCount','comments'];

   	public $timestamps = false;
   	protected $dates = ['deleted_at'];

}
