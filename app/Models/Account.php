<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $connection = 'pgsql';
    protected $table = 'account';
    protected $primaryKey = 'name';

    public $incrementing = false; // varchar pk這個填錯會導致取值異常呢..
    public $timestamps = false; 

    protected $guarded = [];  
}