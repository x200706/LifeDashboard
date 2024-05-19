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
            $create->integer('amount', '餘額'); // 這沒有number方法 表單卻有...
            $create->select('status', '狀態')->options(['open' => '開啟','close' => '關閉',])->default('open')->disable(); // 這邊是裝飾品 實際上預設值是DB給的^q^||
            // 寫法很怪吧，但如果不disable又能改..display只能顯示不能存檔
            // readonly會多出一個x
        });

        $grid->column('name', '戶頭代號'); // 不給改，關聯會亂掉，除非之後做個功能－改了還回調去改舊的紀錄
        $grid->column('desc', '顯示名稱')->editable();
        $grid->column('amount', '餘額'); // 不給改
        $grid->column('status', '狀態')->editable('select', ['open' => '開啟','close' => '關閉',]); // 只能軟刪除

        return $grid;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    { // 經過測試，這邊設定為不能新增的欄位，也不能用快捷新增 @@ 生成的SQL語句會亂掉ㄟ
        $form = new Form(new Account);

        $form->text('name', '戶頭代號');
        $form->text('desc', '顯示名稱');
        $form->number('amount', '餘額');
        $form->select('status', '狀態')->options(['open' => '開啟','close' => '關閉',])->default('open')->disable(); // 我發現disable就算有預設值也不能寫入DB.. 一則實驗，在這邊dis 在快速沒有 答案竟然是可以新增（？？？）
        // 是可以在DB寫預設值啦...那就是說這邊的顯示只是裝飾
        // 話說回來官方文件說 快速創建跟表單型態一致 似乎僅限於var number date 關於你var要用選的還是輸入的 似乎不影響...？！
        return $form;
    }
}
