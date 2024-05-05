<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Account;
use App\Models\AccountRecordTags;


class AccountRecord extends Model
{
    protected $connection = 'pgsql';
    protected $table = 'account_record'; // 記帳軟體不叫支出追蹤 確實有點不國際化 單純叫這個的原因是我覺得帳戶的增加同樣重要 所以這系列的功能 我稱之為「帳戶的紀錄」 應該還說得過去..
    protected $primaryKey = 'id';

    public $incrementing = true; 
    public $timestamps = false; 

    // 哇怎麼這麼耗費記憶體..
    // public function tag()
    // {
    //     return $this->hasOne(AccountRecordTags::class);
    // }

     // ORM Model關聯調用，有點耗記憶體@@
    // public function account()
    // {
    //     return $this->hasOne(Account::class, 'name', 'account');
    // }
}