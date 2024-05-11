<?php

/**
 * Laravel-admin - admin builder based on Laravel.
 * @author z-song <https://github.com/z-song>
 *
 * Bootstraper for Admin.
 *
 * Here you can remove builtin form field:
 * Encore\Admin\Form::forget(['map', 'editor']);
 *
 * Or extend custom form field:
 * Encore\Admin\Form::extend('php', PHPEditor::class);
 *
 * Or require js and css assets:
 * Admin::css('/packages/prettydocs/css/styles.css');
 * Admin::js('/packages/prettydocs/js/main.js');
 *
 */

Encore\Admin\Form::forget(['map', 'editor']);

Admin::style('
.skin-black .main-header>.logo {
    background-color: #24b8bf;
    color: #ffffff;
    border-bottom: 0 solid transparent;
    border-right: #24b8bf;
}

.main-header .sidebar-toggle:before, .fa-refresh:before, span.hidden-xs{
    color: #fff;
}

.logo-mini:hover {
    background-color: #24b8bf;
}

.bootstrap-datetimepicker-widget.dropdown-menu {
    display: contents!important; /*日期選擇器CSS修復*/
}

/*修正有連結的標籤色彩*/
.label.label-danger a.grid-editable-type.editable.editable-click {
    color:#fff;
    border-bottom: dashed 1px #fff;
}
.label.label-success a.grid-editable-type.editable.editable-click {
    color:#fff;
    border-bottom: dashed 1px #fff;
}
');