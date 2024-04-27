<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountRecord extends Model
{
    protected $connection = 'pgsql';
    protected $table = 'account_record';
    protected $primaryKey = 'id';

    public $incrementing = true; 
    public $timestamps = false; 
}