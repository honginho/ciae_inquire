<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PartnerSchool extends Model
{
    //
    use SoftDeletes;
    protected $table ='partner_school';
    protected $fillable=['college','dept','nation',
    					'chtName','engName','startDate',
    					'endDate','comments'];
    public $timestamps=false;
    protected $dates = ['deleted_at'];
}
