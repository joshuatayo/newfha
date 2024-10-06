<?php namespace JoshuaTayo\Report\Controllers;

use Backend;
use BackendMenu;
use Backend\Classes\Controller;

class Reports extends Controller
{
    public $implement = [
        \Backend\Behaviors\FormController::class,
        \Backend\Behaviors\ListController::class
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    public $requiredPermissions = [
        'joshuatayo.report.manage_report.access_reports'
    ];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('JoshuaTayo.Report', 'report-menu', 'reports');
    }

}
