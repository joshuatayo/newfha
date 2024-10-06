<?php namespace JoshuaTayo\AboutInfo\Controllers;

use Backend;
use BackendMenu;
use Backend\Classes\Controller;

class ManagementTeams extends Controller
{
    public $implement = [
        \Backend\Behaviors\FormController::class
    ];

    public $formConfig = 'config_form.yaml';

    public $requiredPermissions = [
        'joshuatayo.aboutinfo.manage_aboutinfo.access_management_teams' 
    ];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('JoshuaTayo.AboutInfo', 'about-info', 'team-members');
    }

    public function index()
    {
        $model = $this->formCreateModelObject()->first();

        if (!$model) {
            $model = $this->formCreateModelObject();
            $model->forceSave();
        }

        return Backend::redirect("joshuatayo/aboutinfo/managementteams/update/{$model->id}");
    }
}
