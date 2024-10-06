<?php namespace JoshuaTayo\Fha\Components;

use Str;
use Validator;
use Cms\Classes\Page;
use Cms\Classes\ComponentBase;
use Illuminate\Support\MessageBag;
use JoshuaTayo\Fha\Models\Settings;
use JoshuaTayo\Fha\Models\Service as ServiceModel;
use JoshuaTayo\Fha\Models\ServiceForm as ServiceFormModel;
use AjaxException;
use Redirect;
use Request;
use Input;
use Session;
use Flash;
use Form;
use Log;
use App;
use Twig;

class ServiceForm extends ComponentBase
{ 
	public $form;

    private $validationRules;
  private $validationMessages;

   private $postData = [];

   private $post;

	public function componentDetails()
    {
        return [
            'name'        => 'ServiceForm',
            'description' => 'Display Service Images on page'
        ];
    }

    public function defineProperties()
    {
        return [
            'slug' => [
                'title'       => 'Slug',
                'type'        => 'string',
                'default'     => '{{ :slug }}'
            ],
            'detailPage' => [
                'title'             => 'Post page',
                'type'              => 'dropdown',
                'group'             => 'Links',
                'options'           => $this->getPageOptions()
            ],
            'redirect_url'      => [
              'title'       => 'redirect url',
              'type'        => 'dropdown',
              'default'     => null,
              'options'           => $this->getPageOptions()
            ],
        ];
    }

    public function getPageOptions()
    {
        return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }

    public function onRun()
    {
        $this->form = $this->page['form'] = $this->loadItem();
         if(Settings::get('recaptcha_enabled', false))
            $this->addJs('https://www.google.com/recaptcha/api.js');
    }

    public function settings(){
        return [
            'recaptcha_enabled' => Settings::get('recaptcha_enabled', false),
            'recaptcha_site_key' => Settings::get('site_key', ''),
            'text_top_form' => Settings::get('text_top_form', ''),

        ];
    }



    /**
   * Form handler
   */
    public function onFormSend(){

        $this->setFieldsValidationRules();
        $this->post = Input::all();
        $file[] = Input::file('file');

        $slug = $this->property('slug');
        $validator = Validator::make($this->post, $this->validationRules, $this->validationMessages);
        $this->setPostData($validator->messages());

        $model = new ServiceFormModel;

        if (Settings::get('recaptcha_enabled', false)){

            $response=json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".Settings::get('secret_key')."&response=".post('g-recaptcha-response')."&remoteip=".$_SERVER['REMOTE_ADDR']), true);
            if($response['success'] == false)
            {
                Flash::error('Human test fail, please check reCAPTCHA checkbox');

                throw new AjaxException(['#simple_contact_flash_message' => $this->renderPartial('@flashMessage.htm')]);
            }

        }

        $model->storeFormData($this->postData, $file, $slug);

        Flash::success('Form Submitted');
        return ['#simple_contact_flash_message' => $this->renderPartial('@flashMessage.htm')];

    }

   /**
   * Get form attributes
   */
    public function getFormAttributes(){

        $attributes = [];

        $attributes['data-request'] = $this->alias . '::onFormSend';
        $attributes['class'] = null;
        $attributes['id'] = 'scf-form-id-' . $this->alias;
        $attributes['data-request-files'] = "data-request-files";
        $attributes['data-request-url'] = "";
        
        return $attributes;
    }
   
    /**
    * Generate field HTML code
    * @return string
    */
    public function getFieldHtmlCode(array $fieldSettings){

        if(empty($fieldSettings['name']) && empty($fieldSettings['type'])){
            return NULL;
        }

      $fieldType = ServiceModel::getFieldTypes($fieldSettings['type']);
      $fieldRequired = $this->isFieldRequired($fieldSettings);

      //If there is a custom code, return it only
      if( !empty($fieldSettings['type']) and $fieldSettings['type'] == 'custom_code' and !empty($fieldSettings['field_custom_code']) ) {

         if( !empty($fieldSettings['field_custom_code_twig']) ) {
           return(Twig::parse($fieldSettings['field_custom_code']));
         } else {
           return($fieldSettings['field_custom_code']);
         }
      }

      $output = [];

      $wrapperCss = ( $fieldSettings['wrapper_css'] ? $fieldSettings['wrapper_css'] : $fieldType['wrapper_class'] );

      // Add wrapper error class if there are any
      if(!empty($this->postData[$fieldSettings['name']]['error'])){
         $wrapperCss .= ' has-error';
      }


      $output[] = '<div class="' . $wrapperCss . '">';


     // Checkbox wrapper
      if ($fieldSettings['type'] == 'checkbox') {
        $output[] = '<div class="checkbox">';
      }

      // Label classic
      if( !empty($fieldSettings['label']) and !empty($fieldType['label']) ){
        $output[] = '<label class="control-label ' . ( !empty($fieldSettings['label_css']) ? $fieldSettings['label_css'] : '' ) . ' ' . ( $fieldRequired ? 'required' : '' ) . '" for="' . ($this->alias . '-' . $fieldSettings['name']) . '">' . $fieldSettings['label'] . '</label>';
      }

      // Label as container
      if( !empty($fieldSettings['label']) and empty($fieldType['label']) ){
         $output[] = '<label class="' . ( !empty($fieldSettings['label_css']) ? $fieldSettings['label_css'] : '' ) . '">';
      }

      // Field attributes
      $attributes = [
         'id' => $this->alias . '-' . $fieldSettings['name'],
         'class' => null
      ];

      $tagClass = $fieldSettings['field_css'] ? $fieldSettings['field_css'] : $fieldType['field_class'];

      if(!empty($tagClass)) {
         $attributes['class'] = $tagClass;
      }

      if(!empty($fieldType['use_name_attribute'])) {
         $attributes['name'] = $fieldSettings['name'];
      }

      if ( !empty($this->postData[$fieldSettings['name']]['value']) && empty($fieldType['html_close']) ) {

         if ($fieldSettings['type'] == 'checkbox') { 
            $attributes['checked'] = null;
         } else {
            $attributes['value'] = $this->postData[$fieldSettings['name']]['value'];
         }
      }

      // Autofocus only when no error
      if(!empty($fieldSettings['autofocus']) && !Flash::error()){
         $attributes['autofocus'] = NULL;
      }

      // Add custom attributes from field settings
      if(!empty($fieldType['attributes'])){
        $attributes = array_merge($attributes, $fieldType['attributes']);
      }

      // Add error class if there are any and autofocus field
      if(!empty($this->postData[$fieldSettings['name']]['error'])){
        $attributes['class'] = $attributes['class'] . ' error is-invalid';

         if(empty($this->errorAutofocus)){
            $attributes['autofocus'] = NULL;
            $this->errorAutofocus = true;
         }
      }

      if($fieldRequired){
         $attributes['required'] = NULL;
      }

      // if($fieldType['use_name_attribute'] = 'file') {
      //   $attributes['multiple'] = NULL;
      // }

      $output[] = '<' . $fieldType['html_open'] . ' ' . $this->formatAttributes($attributes) . '>';

      // For dropdown add options
      if( $fieldSettings['type'] == 'dropdown' && count($fieldSettings['field_values']) ) {

         $valuesCounter = 1;
        
         foreach($fieldSettings['field_values'] as $fieldValue) {

            if( !empty($this->postData[$fieldSettings['name']]['value']) && $this->postData[$fieldSettings['name']]['value'] == $fieldValue['field_value_id'] ){
               $optionAttribute = 'selected';
            } else {
               $optionAttribute = null;
            }

            $output[] = "<option $optionAttribute value='" . $fieldValue['field_value_id'] . "'>" . $fieldValue['field_value_content'] . "</option>";
            $valuesCounter++;
         }
      }

      // For pair tags insert value between
      if(!empty($this->postData[$fieldSettings['name']]['value']) && !empty($fieldType['html_close'])){
         $output[] = $this->postData[$fieldSettings['name']]['value'];
      }

      // For tags without label put text inline
      if( empty( $fieldType['label'] ) ){
        $output[] = $fieldSettings['label'];
      }

      // If there is a custom content
      if (!empty($fieldSettings['type']) and $fieldSettings['type'] == 'custom_content' and !empty($fieldSettings['field_custom_content'])) {
         $output[] = $fieldSettings['field_custom_content'];
      }

      if(!empty($fieldType['html_close'])){
         $output[] = '</' . $fieldType['html_close'] . '>';
      }

      // Label as container
      if( !empty($fieldSettings['label']) and empty($fieldType['label']) ){
         $output[] = '</label>';
      }

      // Checkbox wrapper
      if ($fieldSettings['type'] == 'checkbox') {
         $output[] = '</div>';
      }

      $output[] = "</div>";
    
      return(implode('', $output));
   }




   /**
   * Format attributes array
   * @return array
   */
   private function formatAttributes(array $attributes, $jsArray = false) {

      $output = [];

      foreach ($attributes as $key => $value) {
         $output[] = $key . ($jsArray ? ': "' : '="') . $value . '"';
      }
      return implode(($jsArray ? ', ' : ' '), $output);
   }

   /**
   * Search for required validation type
   */
   private function isFieldRequired($fieldSettings){

      if(empty($fieldSettings['validation'])){
         return false;
      }

      foreach($fieldSettings['validation'] as $rule) {
         if(!empty($rule['validation_type']) && $rule['validation_type'] == 'required'){
            return true;
         }
      }
      return false;
   }


    protected function loadItem()
    {

        $slug = $this->property('slug');
        $form = ServiceModel::isEnabled()->where('slug', $slug)->first();

        return $form;
    }

    /**
   * Generate validation rules and messages
   */
  private function setFieldsValidationRules(){
    $fieldData = $this->loadItem();

    $date = $fieldData['form_fields'];

    $fieldsDefinition = $date;

    $validationRules = [];
    $validationMessages = [];
    foreach($fieldsDefinition as $field){
      
      if(!empty($field['validation'])) {
        $rules = [];
        
        foreach($field['validation'] as $rule) {
          
          if( $rule['validation_type']=='custom' && !empty($rule['validation_custom_type']) ){

            if(!empty($rule['validation_custom_pattern'])) {
              
              switch ($rule['validation_custom_type']) {

                /**
                 * Keep regex pattern in an array
                 */
                case "regex":

                  $rules[] = [$rule['validation_custom_type'], $rule['validation_custom_pattern']];

                break;

                default:

                  $rules[] = $rule['validation_custom_type'] . ':' . $rule['validation_custom_pattern'];

                break;

              }
              
              
              
            } else {
              
              $rules[] = $rule['validation_custom_type'];

            }

            if(!empty($rule['validation_error'])){

              $validationMessages[($field['name'] . '.' . $rule['validation_custom_type'] )] = $rule['validation_error'];
            }  

          } else {

            $rules[] = $rule['validation_type']; 

            if(!empty($rule['validation_error'])){

              $validationMessages[($field['name'] . '.' . $rule['validation_type'] )] = $rule['validation_error'];
            }  
            }
        }

        $validationRules[$field['name']] = $rules;
      }
    }

    $this->validationRules = $validationRules;
    $this->validationMessages = $validationMessages;

  }

    /**
   * Generate post data with errors
   */
  private function setPostData(MessageBag $validatorMessages){
    $fieldData = $this->loadItem();

    $date = $fieldData['form_fields'];

    foreach( $date as $field){
      $this->postData[ $field['name'] ] = [
        'value' => e(Input::get($field['name'])),
        'error' => $validatorMessages->first($field['name']),
      ];

    }

  }
}