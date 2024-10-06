<?php namespace JoshuaTayo\Fha\Controllers;

use Backend;
use BackendMenu;
use Backend\Classes\Controller;

class Videos extends Controller
{
    public $implement = [
        \Backend\Behaviors\FormController::class,
        \Backend\Behaviors\ListController::class
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('JoshuaTayo.Fha', 'federalhousingauthority', 'videos');
    }

}
