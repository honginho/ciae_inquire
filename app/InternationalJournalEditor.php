<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InternationalJournalEditor extends Model
{
	use SoftDeletes;
    protected $table ='international_journal_editor';
    protected $fillable=['college','dept','name',
    					'journalName',
    					'startDate','endDate','comments'];
    public $timestamps=false;
    protected $dates = ['deleted_at'];
}
