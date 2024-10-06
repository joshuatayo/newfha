<?php namespace JoshuaTayo\Fha\Controllers;

use Backend\Classes\Controller;
use BackendMenu;
use JoshuaTayo\Fha\Models\ServiceForm;
use JoshuaTayo\Fha\Models\Service;

class ServiceForms extends Controller
{
    public $implement = [        
    	'Backend\Behaviors\ListController',        
    	// 'Backend\Behaviors\FormController',        
    	// 'Backend\Behaviors\ReorderController'    
    ];
    
    public $listConfig = 'config_list.yaml';
    // public $formConfig = 'config_form.yaml';
    // public $reorderConfig = 'config_reorder.yaml';

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('JoshuaTayo.Fha', 'messages', 'serviceforms');
    }

    public static function countUnreadForm()
    {
        $counter = ServiceForm::where('is_new', true)->count();

        if ($counter > 0)
            return $counter;
        else
            return null;
    }

    /**
     * Preview page view
     * @param $id
     */
    public function preview( $id ){

    	$serviceform = ServiceForm::find( $id );
    	$service = Service::find( $id );
    	$this->pageTitle = "Services Submitted Forms";

        if ( $serviceform ) {

            $this->vars['serviceform'] = $serviceform;
            $serviceform->is_new = 0;
            $serviceform->save();

        } else{

            Flash::error("Record Not Found");
            return Redirect::to( Backend::url( 'joshuatayo/fha/submitted_form' ) );

        }

    }


    /**
     * Index page view
     */
    public function index(){

        parent::index();
    }
}
