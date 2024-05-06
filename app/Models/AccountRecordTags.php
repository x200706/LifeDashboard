<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountRecordTags extends Model
{
    protected $connection = 'pgsql';
    protected $table = 'account_record_tags';
    protected $primaryKey = 'name';

    public $incrementing = false; 
    public $timestamps = false; 

    protected $guarded = [];  
}