<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bulletin extends Model
{
    protected $connection = 'pgsql';
    protected $table = 'bulletin';
    protected $primaryKey = 'id';

    public $incrementing = true; 
    public $timestamps = false; // 用PostgreSQL的預設值跟now函數就好

    protected $guarded = [];  
}