<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $connection = 'pgsql';
    protected $table = 'account';
    protected $primaryKey = 'name';

    public $incrementing = true; 
    public $timestamps = false; 
}