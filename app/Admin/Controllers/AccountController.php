<?php

namespace App\Admin\Controllers;

use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

use App\Models\Account;

class AccountController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '資產帳戶管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Account);

        $grid->disableCreateButton(); // 禁用新增按鈕
        $grid->disableActions(); // 禁用單行異動按鈕
        $grid->disableFilter(); // 禁用漏斗
        $grid->disableExport(); // 禁用匯出
        $grid->disableRowSelector(); // 禁用選取
        $grid->disableColumnSelector(); // 禁用像格子圖案的按鈕

        $grid->quickCreate(function (Grid\Tools\QuickCreate $create) {
            $create->text('name', '類別代號');
            $create->text('desc', '顯示名稱');
            $create->text('amount', '餘額'); //TODO 型態不對
            $create->text('status', '狀態'); //TODO 型態不對；應該用選項卡
        });

        $grid->column('name', '類別代號')->editable();
        $grid->column('desc', '顯示名稱')->editable();
        $grid->column('amount', '餘額'); // 不給改
        $grid->column('status', '狀態')->editable(); // 只能軟刪除

        return $grid;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Account);

        $form->text('name', '戶頭代號');
        $form->text('desc', '顯示名稱');
        $form->text('amount', '餘額'); //TODO 型態不對
        $form->text('status', '狀態'); //TODO 型態不對；應該用選項卡

        return $form;
    }
}
