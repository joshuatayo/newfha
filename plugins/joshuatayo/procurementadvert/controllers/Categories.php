<?php namespace JoshuaTayo\ProcurementAdvert\Controllers;

use Backend;
use BackendMenu;
use Backend\Classes\Controller;

class Categories extends Controller
{
    public $implement = [
        \Backend\Behaviors\FormController::class,
        \Backend\Behaviors\ListController::class
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    public $requiredPermissions = [
        'joshuatayo.procurementadvert.manage_procurementadvert.access_categories' 
    ];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('JoshuaTayo.ProcurementAdvert', 'procurement-advert-menu', 'categories');
    }

}
