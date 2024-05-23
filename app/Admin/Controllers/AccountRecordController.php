<?php

namespace App\Admin\Controllers;

use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

use Illuminate\Support\Facades\Log;

use App\Models\AccountRecord;
use App\Models\Account;
use App\Models\AccountRecordTags;

class AccountRecordController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '記帳管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new AccountRecord);

        $grid->model()->orderBy('date', 'desc');

        $grid->disableCreateButton(); // 禁用新增按鈕
        $grid->disableFilter(); // 禁用漏斗
        $grid->disableExport(); // 禁用匯出
        $grid->disableRowSelector(); // 禁用選取
        $grid->disableColumnSelector(); // 禁用像格子圖案的按鈕

        // 禁用個別單行異動按鈕
        $grid->actions(function ($actions) {        
            // 去掉编辑
            $actions->disableEdit();
            // 去掉查看
            $actions->disableView();
        });
        

        $grid->quickCreate(function (Grid\Tools\QuickCreate $create) { // 注意到匿名函數裡面可以用最外面的use？！
            $create->date('date', '日期');
            $create->text('name', '名稱');
            $create->select('type', '收支類型')->options(['income' => '❇️收入','expense' => '🔻支出',]);
            $create->select('tag', '記帳分類')->options(AccountRecordTags::all()->pluck('desc','name'));
            $create->integer('amount', '金額');
            $create->select('account', '帳戶')->options(Account::all()->pluck('desc','name')); // 根據官方文件 使用belongTo可以顯示更多
        });

        $grid->column('date', '日期')->editable('date');
        $grid->column('name', '名稱')->editable();
        $grid->column('type', '收支類型')->editable('select', ['income' => '❇️收入','expense' => '🔻支出',]);

        // 因為editable跟display混用導致介面異常（如下方寫法時欄位顯示會變成一隻筆，如果先editable再下display則無更改顯示作用），震驚發現editable會自己對應上陣列內容
        // ^20240512這延伸一個問題，萬一我想顯示美化過的選項怎麼辦...？
        // $grid->column('type', '收支類型')->display(function ($type) {
        //     if ($type == 'income'){
        //         return "<span style='color:green'>$type</span>";
        //     } else {
        //         return "<span style='color:red'>$type</span>";
        //     }
        // })->editable('select', ['income' => '收入','expense' => '支出',]);

        $grid->column('tag', '記帳分類')->editable('select', AccountRecordTags::all()->pluck('desc','name'));

        // 這種單純狀況也是能一開始grid就調用model()做join 不過因為涉及另外兩張表 之前測過這邊leftJoin可有問題的 用關係或手動查吧
        $grid->column('amount', '金額')->editable();
        $grid->column('account', '帳戶')->editable('select', Account::all()->pluck('desc','name'));

        return $grid;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $call = $this;
        
        $form = new Form(new AccountRecord);
        
        // 表單驗證寫在這邊就好了，上方有沒有寫沒差
        // 但已知有個送出提示按鈕卡住的bug orz
        $form->date('date', '日期')->rules('required');
        $form->text('name', '名稱')->rules('required|min:1');
        $form->select('type', '收支類型')->options(['income' => '增加','expense' => '減少',])->rules('required');
        $form->select('tag', '記帳分類')->options(AccountRecordTags::all()->pluck('desc','name'))->rules('required');
        $form->number('amount', '金額')->rules('required|regex:/^[1-9][0-9]*$/|min:1');
        $form->select('account', '帳戶')->options(Account::all()->pluck('desc','name'))->rules('required');

        $form->saving(function (Form $form) use ($call) {
            if (is_null($form->model()->id)) { // 首次新增（存檔前還沒執行SQL，自然也不會在DB自增id，所以也查不到這筆資料的id）
                if ($form->type == 'income') {
                    $call->updateAccountAmount($form->account, $form->amount);
                } else {
                    $call->updateAccountAmount($form->account, -$form->amount);
                } 
            } else { // 更新操作
                $call->updateAccountReferColum($form);
            }
        });

        return $form;
    }

    // 檢查是否需要更新帳戶金額並調用異動帳戶金額方法
    function updateAccountReferColum($form) {
        // 新的值 沒有更新就空的
        $newRecordType = $form->type;
        $newRecordAmount = $form->amount;
        $newRecordAccount = $form->account;

        // 舊的值
        $originRecordType = $form->model()->type;
        $originRecordAmount = $form->model()->amount;
        $originRecordAccount = $form->model()->account;

        if (!is_null($newRecordType) && ($newRecordType != $originRecordType)) { // 收支類型變化
            $amountChange = ($newRecordType == 'income') ? $originRecordAmount * 2 : -$originRecordAmount * 2;
            $this->updateAccountAmount($originRecordAccount, $amountChange);
        } elseif (!is_null($newRecordAmount) && $newRecordAmount != $originRecordAccount) { // 金額變化
            $amountChange = $newRecordAmount - $originRecordAmount;                                                          
            if ($originRecordType == 'income') {
                $this->updateAccountAmount($originRecordAccount, $amountChange);
            } else {
                $this->updateAccountAmount($originRecordAccount, -$amountChange);
            }                                                    
        } elseif (!is_null($newRecordAccount) && $newRecordAccount != $originRecordAccount) { // 帳戶變化
            $amountChange = ($originRecordType == 'income') ? -$originRecordAmount : $originRecordAmount;
            $this->updateAccountAmount($originRecordAccount, $amountChange);

            $transAmountChange = ($originRecordType == 'income') ? $originRecordAmount : -$originRecordAmount;
            $this->updateAccountAmount($newRecordAccount, $transAmountChange);
        }
    }

    // 異動帳戶金額
    function updateAccountAmount($accountPk, $amountChange) {
        $account = Account::find($accountPk);
        $currentAmount = $account->amount;
        $newAmount = $currentAmount + $amountChange;

        $account->update(['amount' => $newAmount]);
    }
}
