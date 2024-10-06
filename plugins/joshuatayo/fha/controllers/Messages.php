<?php namespace JoshuaTayo\Fha\Controllers;

use Backend\Classes\Controller;
use BackendMenu;
use JoshuaTayo\Fha\Classes\JHelper;
use October\Rain\Support\Facades\Flash;
use JoshuaTayo\Fha\Models\Message as MessageModel;
use Illuminate\Support\Facades\Redirect;
use Mail;
use Validator;
use ValidationException;
use System\Classes\SettingsManager;

class Messages extends Controller
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
        BackendMenu::setContext('JoshuaTayo.Fha', 'messages', 'contactform');
    }

    public function listInjectRowClass($record, $definition = null)
    {
        if ($record->new_message) {
            return 'new';
        }
    }

    public static function countUnreadMessages()
    {
        $counter = MessageModel::where('new_message', true)->count();

        if ($counter > 0)
            return $counter;
        else
            return null;
    }

    public function onDelete()
    {
        /** Check if this is even set **/
        

        if (($checkedIds = post('checked')) && is_array($checkedIds) && count($checkedIds)) {

            SimpleContactModel::whereIn('id',$checkedIds)->delete();

        }

        $counter = MessageModel::where('new_message', true)->count();

        return [
            'counter' => $counter,
            'list' => $this->listRefresh()
            ];
    }

    public function onDeleteSingle(){
        $id = post('id' );
        $record = SimpleContactModel::find($id);

        if($record){

            $record->delete();
            Flash::success('Successfully deleted the message');
        }
        else{
            Flash::error('Something went wrong, unable to delete.');
        }


        return Redirect::to(Backend::url('joshuatayo/fha/messages'));
    }

    public function preview( $id ) 
    {
    	$message = MessageModel::find( $id );
        if ( $message ) {
        	$message->new_message = false;
            $message->save();

        	$this->addCss("/plugins/joshuatayo/fha/assets/css/backend-custom.css", "1.0.0");
            $this->addCss("/plugins/joshuatayo/fha/assets/css/print-message.css", "media=\"print\"");
            $this->addJs("/plugins/joshuatayo/fha/assets/js/printThis.js");
            $this->addJs("/plugins/joshuatayo/fha/assets/js/simpleContact.js");

        	$this->pageTitle = "Inbox";
        	$this->vars['message'] = $message;
            $this->vars['avatar'] = JHelper::get_gravatar($message->email);
        } else{
            Flash::error('Message not found');
            return Redirect::to( Backend::url( 'joshuatayo/fha/messages' ) );
        }
    }

    /**
     * send message reply
     */
    public function onReplyMessage(){


        $formValidationRules = [
            'subject' => ['required'],
            'message' => ['required']
        ];

        $validator = Validator::make(post(), $formValidationRules);
        // Validate
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $record = MessageModel::find(post('id'));

        if($record){

            $vars = [
                'message_body' => nl2br(post('message'))
            ];

            Mail::send('joshuatayo.fha::mail.reply', $vars, function($message) {

                $message->to(post('email_to'), post('name_to'));
                $message->subject(post('subject'));

            });

            $record->is_replied = true;
            $record->save();

            Flash::success(e(trans('Message send successfully.')));
        }
        else {
            Flash::error(e(trans('Something went wrong, unable to send reply.')));
        }

    }
}
