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

.main-header .sidebar-toggle:before {
    content: "\f0c9";
    color: #8c9fa1;
}

.logo-mini:hover {
    background-color: #24b8bf;
}
');