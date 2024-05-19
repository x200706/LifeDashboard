<?php

namespace App\Admin\Controllers;

use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

use Encore\Admin\Facades\Admin; // https://github.com/z-song/laravel-admin/issues/3147

use App\Models\Bulletin;

class HomeController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '首頁∥佈告欄';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Bulletin);
        //TODO 新的文章在上方
        // $grid->disableCreateButton(); // 禁用新增按鈕
        // $grid->disableActions(); // 禁用單行異動按鈕
        // $grid->disableFilter(); // 禁用漏斗
        $grid->disableExport(); // 禁用匯出
        $grid->disableRowSelector(); // 禁用選取
        $grid->disableColumnSelector(); // 禁用像格子圖案的按鈕

        $grid->filter(function (Grid\Filter $filter) {
            $filter->expand();
            $filter->disableIdFilter();
            $filter->like('title', '文章標題');
            // $filter->equal('tag', '分類')->select(Bulletin::select('tag')->distinct()->get());
        });

        $grid->column('title', '文章標題'); 
        $grid->column('tag', '分類'); 
        $grid->column('created_at', '發文日期')->date('Y-m-d H:m')->sortable(); 
        $grid->column('creator', '發文者'); 

        //TODO 關聯顯示

        return $grid;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Bulletin);

        $form->select('ref_bulletin', '關聯文章')->options(Bulletin::whereNull('ref_bulletin')->pluck('title','id')); // 只有首篇可以被關聯
        $form->text('title', '文章標題')->required(); //TODO 如果他是關聯文章 不可以改標題；如果是全新討論，可以自己取標題名稱
        $form->text('tag', '分類')->required();
        $form->textarea('content', '內文')->placeholder('請使用HTML')->required();
        $form->text('creator', '發文者')->default(Admin::user()->username)->readonly();
        $form->datetime('create_at', '發文日期')->default(date())->readonly();
        
        return $form;
    }

    protected function detail($id)
    {
        $show = new Show(Bulletin::findOrFail($id));

        $show->field('title', '文章標題');
        $show->field('tag', '分類');
        // $show->field('content', '內文')->as(function ($content) {
        //     return "{$content}";
        // });
        $show->field('created_at', '發文日期');
        $show->field('creator', '發文者');

        return $show;
    }
}
