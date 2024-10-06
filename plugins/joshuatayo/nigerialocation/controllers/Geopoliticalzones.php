<?php namespace JoshuaTayo\NigeriaLocation\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use System\Classes\SettingsManager;

class Geopoliticalzones extends Controller
{
    public $implement = [        
    	'Backend\Behaviors\ListController',        
    	'Backend\Behaviors\FormController',       
    	'Backend\Behaviors\ReorderController'  
    ];
    
    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';
    public $reorderConfig = 'config_reorder.yaml';

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('October.System', 'system', 'settings');
        SettingsManager::setContext('JoshuaTayo.NigeriaLocation', 'geopoliticalzones');
    }
}
