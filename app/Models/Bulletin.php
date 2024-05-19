<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bulletin extends Model
{
    protected $connection = 'pgsql';
    protected $table = 'bulletin';
    protected $primaryKey = 'id';

    public $incrementing = true; 
     // 本來想用PostgreSQL的預設值跟now函數就好，後來發現坑有點多，遂改回從這邊傳（雖然坑也踩了不少就是了）
    public $timestamps = true;

    // 是說如果有created_at欄位 但關閉時間戳 表單那邊也不給填??（待解謎）

    const CREATED_AT = 'created_at';
    const UPDATED_AT = null; // 如果不設為NULL會自己多傳...

    protected $guarded = [];  
}