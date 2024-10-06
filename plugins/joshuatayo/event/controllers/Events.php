<?php namespace JoshuaTayo\Event\Controllers;

use Backend;
use BackendMenu;
use Backend\Classes\Controller;

class Events extends Controller
{
    public $implement = [
        \Backend\Behaviors\FormController::class,
        \Backend\Behaviors\ListController::class
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    public $requiredPermissions = [
        'joshuatayo.event.manage_events.access_events'
    ];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('JoshuaTayo.Event', 'manage-events', 'events');
    }

}
