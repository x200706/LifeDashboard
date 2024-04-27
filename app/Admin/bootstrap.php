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

Admin::style('.skin-blue-light .main-header .navbar { background-color: #191d37; } .skin-blue-light .main-header .logo { background-color: #4e5bad; color: #fff; border-bottom: 0 solid transparent; } .skin-blue-light .main-header .logo:hover { background-color: #4e5bad; } .skin-blue-light .main-header .navbar .sidebar-toggle:hover { background-color: #4e5bad; } .bg-light-blue, .label-primary, .modal-primary .modal-body { background-color: #606fd2 !important; } .skin-blue-light .main-header li.user-header { background-color: #a6a0e8; }');
