<?php

namespace App\Admin\Controllers;

use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

use App\Models\AccountRecord;

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
            $create->text('tag', '記帳分類'); //TODO 關聯選擇
            $create->integer('amount', '金額');
            $create->text('account', '帳戶'); //TODO 關聯選擇
            // https://github.com/z-song/laravel-admin/issues/4223
            // belongTo可以顯示更多
        });


        $grid->column('date', '日期');
        $grid->column('name', '名稱');
        $grid->column('type', '收支類型'); //TODO 根據收支變色 收為綠支為紅
        $grid->column('tag', '記帳分類'); //TODO 關聯顯示
        // 這種單純狀況是能一開始grid就join 但在模型裡寫關係 也是一種充滿美德的新模式
        $grid->column('amount', '金額')->sortable(); //TODO 修改得異動帳戶..
        $grid->column('account', '帳戶'); //TODO 關聯顯示

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
        $form->text('tag', '記帳分類'); //TODO 關聯選擇
        $form->number('amount', '金額');
        $form->text('account', '帳戶'); //TODO 關聯選擇

        // 有沒有可能回調會從這裡來?!

        return $form;
    }
}
