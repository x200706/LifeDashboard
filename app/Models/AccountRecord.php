<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountRecord extends Model
{
    protected $connection = 'pgsql';
    protected $table = 'account_record'; // 記帳軟體不叫支出追蹤 確實有點不國際化 單純叫這個的原因是我覺得帳戶的增加同樣重要 所以這系列的功能 我稱之為「帳戶的紀錄」 應該還說得過去..
    protected $primaryKey = 'id';

    public $incrementing = true; 
    public $timestamps = false; 
}