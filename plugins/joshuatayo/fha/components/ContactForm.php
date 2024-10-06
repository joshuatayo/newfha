<?php namespace JoshuaTayo\Fha\Components;

use Backend\Facades\Backend;
use Cms\Classes\ComponentBase;
use JoshuaTayo\Fha\Models\Settings;
use JoshuaTayo\Fha\Models\Message as MessageModel;
use October\Rain\Support\Facades\Flash;
use Validator;
use AjaxException;
use Mail;
use Redirect;

class ContactForm extends ComponentBase
{
   
    public function componentDetails()
    {
        return [
            'name'        => 'ContactForm',
            'description' => 'Add contact us form to page',

        ];
    }

    public function defineProperties()
    {
        return [
            'nameTitle' => [
                'title' => 'Name field label',
                'description' => 'Name label will appear above the field(required)',
                'default' => 'Full Name',
                'type' => 'string',
                'required' => true,
                'validationMessage' => 'Title label required'
            ],
            'emailTitle' => [
                'title' => 'Email field label',
                'description' => 'Email field will appear above the field(required)',
                'default' => 'Email',
                'type' => 'string',
                'required' => true,
                'validationMessage' => 'Email label required'
            ],
            'phoneTitle' => [
                'title' => 'Phone label',
                'description' => 'Phone label will appear above the field(required)',
                'default' => 'Phone Number',
                'type' => 'string',
                'required' => true,
                'validationMessage' => 'Email label required'
            ],
            'subjectTitle' => [
                'title' => 'Subject field label',
                'description' => 'Subject label will appear above the field(required)',
                'default' => 'Subject',
                'type' => 'string',
                'required' => true,
                'validationMessage' => 'Subject label required'
            ],
            'messageTitle' => [
                'title' => 'Message label',
                'description' => 'Message label will appear above the field(required)',
                'default' => 'Message',
                'type' => 'string',
                'required' => true,
                'validationMessage' => 'Message label required'

            ],
            'buttonText' => [
                'title' => 'Form submit button text',
                'description' => 'Form submit button text(required)',
                'default' => 'Submit',
                'type' => 'string',
                'required' => true,
                'validationMessage' => 'Button text required'

            ],
            'detailedErrors' => [
                'title'       => 'Detailed Errors',
                'description' => 'Show all validation errors using a list',
                'default'     => false,
                'type'        => 'checkbox',
            ],
        ];
    }

    public function properties(){
        return [
            'nameLabel' => $this->property('nameTitle','Full Name'),
            'emailLabel' => $this->property('emailTitle','Email'),
            'phoneLabel' => $this->property('phoneTitle','Phone No.'),
            'subjectLabel' => $this->property('subjectTitle','Subject'),
            'messageLabel' => $this->property('messageTitle','Message'),
            'buttonText' => $this->property('buttonText','Submit'),
            'detailedErrors' => $this->property('detailedErrors',false),
        ];
    }

    public function settings(){
        return [
            'recaptcha_enabled' => Settings::get('recaptcha_enabled', false),
            'recaptcha_site_key' => Settings::get('site_key', ''),
            'text_top_form' => Settings::get('text_top_form', ''),

        ];

    }


    /**
     * Injecting Assets
     */
    public function onRun()
    {
        $this->addJs('/plugins/joshuatayo/fha/assets/js/simpleContact-frontend.js');
        if(Settings::get('recaptcha_enabled', false))
            $this->addJs('https://www.google.com/recaptcha/api.js');
    }

    /**
     * AJAX form fubmit handler
     */
    public function onFormSubmit(){

        /**
         * Form validation
         */
        $customValidationMessages = [
            'name.required' => 'We need to know your name!',
            'email.required' => 'Email address is required',
            'email.email' => 'Please provide a valid email address!',
            'message.phone_number' => 'Message field is required!',
            'subject.required' => 'Please provide subject of your message!',
            'message.required' => 'Message field is required!',
        ];

        $formValidationRules = [
            'name' => 'required',
            'email' => 'required|email',
            'phone_number' => 'required',
            'subject' => 'required',
            'message' => 'required',
        ];

        $validator = Validator::make(post(), $formValidationRules,$customValidationMessages);

        if ($validator->fails()) {

            $messages = $validator->messages();
            Flash::error($messages->first());

            throw new AjaxException(['#simple_contact_flash_message' => $this->renderPartial('@flashMessage.htm',[
                'errors' => $messages->all()
            ])]);
        }

        /**
         * Validating reCaptcha
         */
        if (Settings::get('recaptcha_enabled', false)){

            $response=json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".Settings::get('secret_key')."&response=".post('g-recaptcha-response')."&remoteip=".$_SERVER['REMOTE_ADDR']), true);
            if($response['success'] == false)
            {
                Flash::error('Human test fail, please check reCAPTCHA checkbox');

                throw new AjaxException(['#simple_contact_flash_message' => $this->renderPartial('@flashMessage.htm')]);
            }

        }


        /**
         * At this point all validations succeded
         * further processing form
         */

         $this->submitForm();



        if(Settings::get('redirect_to_page',false) && !empty(Settings::get('redirect_to_url','')))
            return Redirect::to(Settings::get('redirect_to_url'));
        else{
            Flash::success(Settings::get('success_message','Thankyou for contacting us'));
            return ['#simple_contact_flash_message' => $this->renderPartial('@flashMessage.htm')];
        }


    }

    protected function submitForm(){

        $model = new MessageModel;

        $model->name = post('name');
        $model->email = post('email');
        $model->phone_number = post('phone_number');
        $model->subject = post('subject');
        $model->message = post('message');
        $model->new_message = true;
        $model->save();

        if(Settings::get('recieve_notification',false) && !empty(Settings::get('notification_email_address','')))
            $this->sendNotificationMail($model->id);

        if(Settings::get('auto_reply',false))
            $this->sendAutoReply();
    }

    /**
     * Send notification email
     */
    protected function sendNotificationMail($message_id){
        $url_message = Backend::url('joshuatayo/fha/view/'.$message_id);
        $vars = [
            'url_message' => $url_message,
            'name' => post('name'),
            'email' => post('email'),
            'phone_number' => post('phone_number'),
            'subject' => post('subject'),
            'message_body' => post('message')
        ];

        Mail::send('joshuatayo.fha::mail.notification', $vars, function($message) use ($vars) {
             $message->to(Settings::get('notification_email_address'));
             $message->replyTo($vars['email'], $vars['name']);
        });
    }

    /**
     * send auto reply
     */
    protected function sendAutoReply(){

        $vars = [
            'name' => post('name'),
            'email' => post('email'),
            'phone_number' => post('phone_number'),
            'subject' => post('subject'),
            'message_body' => post('message')
        ];

        Mail::send('joshuatayo.fha::mail.auto-response', $vars, function($message) {

            $message->to(post('email'), post('name'));

        });

    }
}
