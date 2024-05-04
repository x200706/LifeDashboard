<?php

namespace App\Admin\Controllers;

use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

use App\Models\AccountRecordTags;

class AccountRecordTagsController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '記帳類別管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new AccountRecordTags);

        // $grid->disableCreateButton(); // 禁用新增按鈕
        // $grid->disableActions(); // 禁用單行異動按鈕
        // $grid->disableFilter(); // 禁用漏斗
        // $grid->disableExport(); // 禁用匯出
        $grid->disableRowSelector(); // 禁用選取
        $grid->disableColumnSelector(); // 禁用像格子圖案的按鈕

        $grid->column('name', '類別代號');
        $grid->column('desc', '顯示名稱');

        return $grid;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new AccountRecordTags);

        $form->display('name', '類別代號');
        $form->display('desc', '顯示名稱');

        return $form;
    }
}
