<?php

namespace App\Admin\Controllers;

use Encore\Admin\Controllers\AdminController;
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
            $create->select('type', '收支類型')->options(['income' => '收入','expense' => '支出',]);
            $create->select('tag', '記帳分類')->options(AccountRecordTags::all()->pluck('desc','name'));
            $create->integer('amount', '金額');
            $create->select('account', '帳戶')->options(Account::all()->pluck('desc','name')); // 根據官方文件 使用belongTo可以顯示更多
        });

        $grid->column('date', '日期')->editable('date'); //TODO 由新到舊
        $grid->column('name', '名稱')->editable();
        $grid->column('type', '收支類型')->editable('select', ['income' => '收入','expense' => '支出',])->label([
            'income' => 'success',
            'expense' => 'danger',
        ]);
        $grid->column('tag', '記帳分類')->editable('select', AccountRecordTags::all()->pluck('desc','name')); // 震驚發現 因為跟display混用導致介面異常，發現editable會自己對應上陣列內容
        // 這種單純狀況也是能一開始grid就調用model()做join 不過因為涉及另外兩張表 之前測過這邊leftJoin可有問題的 用關係或手動查吧
        $grid->column('amount', '金額')->sortable()->editable();
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
        $form = new Form(new AccountRecord);

        $form->date('date', '日期');
        $form->text('name', '名稱');
        $form->select('type', '收支類型')->options(['income' => '收入','expense' => '支出',]);
        $form->select('tag', '記帳分類')->options(AccountRecordTags::all()->pluck('desc','name'));
        $form->number('amount', '金額');
        $form->select('account', '帳戶')->options(Account::all()->pluck('desc','name'));

        //TODO 根據收支類型異動帳戶金額
        $form->saving(function (Form $form) {
            // 行內編輯一次只能改一格嘛～所以一次只進一種判斷式
            // 寫法有點醜ˇWˇ

            // 1. 非首次新增才會進到這裡（首次新增時且存檔前還沒執行SQL，DB裡沒這資料，自然也會自增id，所以根本查不到東西）
            if (!($form->model()->id === null)) {

                // 變了定義：表單收到的，跟更新前的資料庫內容不同，如果選一樣送出的就不會進入各個條件，請放心
                if ($form->type != $form->model()->type) { //TODO 2. 如果收支類型變了->異動當前帳戶金額
                    $amount = $form->model()->type; // 要異動的量
                    switch ($form->type) {
                        case 'income': // 從支出變為收入
                            break;
                        case 'expense': // 從收入變為支出
                            break;
                    }
                } elseif ($form->amount != $form->model()->amount) { //TODO 3. 如果金額變了->異動當前帳戶金額
                    // 先退回原本的金額
                    // 抓取DB收支類型後進行金額異動
                } elseif ($form->account != $form->model()->account) { //TODO 4. 如果帳戶變了->回滾當前帳戶金額 異動新帳戶金額
                    
                }
            } 
            // 補充：自增是DB做的，表單也不會送出id；行內修改都是update那個欄位而已，表單也不會送出id
        
        });

        //TODO 刪除記帳的回滾動作

        return $form;
    }
}
