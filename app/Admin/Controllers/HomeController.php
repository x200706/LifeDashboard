<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\Dashboard;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;

class HomeController extends Controller
{
    public function index(Content $content)
    {
        return $content
            ->title('生活後台')
            ->description('適用於各種共同生活群體的管理系統')
            ->row(function (Row $row) {

                $row->column(4,'首頁'); //TODO 填充首頁內容

                $row->column(4,'最新消息');

                $row->column(4,'快速導覽');
            });
    }
}
