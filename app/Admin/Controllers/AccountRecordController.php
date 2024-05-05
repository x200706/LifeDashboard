<?php

namespace App\Admin\Controllers;

use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

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
        

        $grid->quickCreate(function (Grid\Tools\QuickCreate $create) {
            $create->date('date', '日期');
            $create->text('name', '名稱');
            $create->select('type', '收支類型')->options(['income' => '收入','expense' => '支出',]);
            $create->select('tag', '記帳分類')->options(AccountRecordTags::all()->pluck('desc','name'));
            $create->integer('amount', '金額');
            $create->select('account', '帳戶')->options(Account::all()->pluck('desc','name'));
            // 根據官方文件 使用belongTo可以顯示更多
        });


        $grid->column('date', '日期');
        $grid->column('name', '名稱');
        $grid->column('type', '收支類型')->using(['income' => '收入','expense' => '支出',]); //TODO 根據收支變色 收為綠支為紅
        $grid->column('tag', '記帳分類')->display(function ($tag) {
            return AccountRecordTags::find($tag)->desc; // 有點N+1味的手動關聯orz||
        });
        // 這種單純狀況也是能一開始grid就調用model()做join 不過因為涉及另外兩張表 之前測過這邊leftJoin可有問題的 用關係或手動查吧
        $grid->column('amount', '金額')->sortable(); //TODO 修改得異動帳戶..
        $grid->column('account', '帳戶')->display(function ($account) {
            return Account::find($account)->desc;
        });

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

        // 有沒有可能回調會從這裡來?!

        return $form;
    }
}
