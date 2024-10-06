<?php namespace JoshuaTayo\Fha\Models;

use Model;
use Carbon\Carbon;
use System\Classes\PluginManager;

/**
 * Model
 */
class Service extends Model
{
    use \October\Rain\Database\Traits\Validation;

    public $implement = ['RainLab.Translate.Behaviors.TranslatableModel'];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'joshuatayo_fha_services';

    public $translatable = [
        'form_fields',
    ];

    // protected $guarded = [];

    protected $jsonable = ['form_fields'];
    

    /**
     * Try to use Rainlab Tranlaste plugin to get translated content or falls back to default settings value
     */
    public static function getTranslated($value, $defaultValue = false){

        return Service::get($value, $defaultValue);

    }

    /**
     * Try to use Rainlab Tranlaste plugin to get translated content for given key
     */
    public static function getDictionaryTranslated($value){

        // Check for Rainlab.Translate plugin
        $translatePlugin = PluginManager::instance()->findByIdentifier('Rainlab.Translate');

        if ($translatePlugin && !$translatePlugin->disabled) {

            $params = [];

            $message = \RainLab\Translate\Models\Message::trans($value, $params);

            return $message;

        } else {
            return $value;
        }

    }

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    /**
     * @var array Relation
     */
    public $attachOne = [
        'service_file' => ['System\Models\File'],
        'service_image' => ['System\Models\File'],
    ];

    /**
     * @var array Get Attribute
     */
    public function getSrcimageAttribute($value, $w = 360, $h = 370)
    {
        return $this->service_image->getThumb($w, $h, ['mode' => 'crop']);
    }

    /**
   * Generate form fields types list
   *  @return array
   */
    public function getTypeOptions($value, $formData)
    {

        $fieldTypes = $this->getFieldTypes();

        $types = [];

        if(!$fieldTypes) {
          return [];
        }

        foreach ($fieldTypes as $key => $value) {
          $types[$key] = 'joshuatayo.fha::lang.settings.form_field_types.'.$key;
        }

        return $types;
    }

    /**
    * Generate fields list
    * @return arry
    */
    private function getFieldsList($type = false, $forceType = false){

        $output = [];
        $outputAll = [];

        foreach (Service::get('form_fields', []) as $field) {

            $fieldName = $field['name'] . ' ['. $field ['type'] . ']';

            $outputAll[$field['name']] = $fieldName;

            if($type && !empty($field['type']) && $field['type'] <> $type) {
                continue;
            } else {
                $output[$field['name']] = $fieldName;
            }

        }

        if($forceType) {
            return $output;
        } else {
            return $outputAll;
        }
    }

    /**
       * HTML field types mapping array
       * @return array
       */
    public static function getFieldTypes($type = false) 
    {

        $types = [
            'text' => [
                'html_open' => 'input',
                'label' => true,
                'wrapper_class' => 'form-group',
                'field_class' => 'form-control',
                'use_name_attribute' => true,
                'attributes' => [
                  'type' => 'text',
                ],
                'html_close' => null,
            ],

            'email' => [
                'html_open' => 'input',
                'label' => true,
                'wrapper_class' => 'form-group',
                'field_class' => 'form-control',
                'use_name_attribute' => true,
                'attributes' => [
                  'type' => 'email',
                ],
                'html_close' => null,
            ],

            'textarea' => [
                'html_open' => 'textarea',
                'label' => true,
                'wrapper_class' => 'form-group',
                'field_class' => 'form-control',
                'use_name_attribute' => true,
                'attributes' => [
                  'rows' => 5,
                ],
                'html_close' => 'textarea',
            ],

            'checkbox' => [
                'html_open' => 'input',
                'label' => false,
                'wrapper_class' => null,
                'field_class' => null,
                'inner_label' => true,
                'use_name_attribute' => true,
                'attributes' => [
                  'type' => 'checkbox',
                ],
                'html_close' => null,
            ],

            'dropdown' => [
                'html_open' => 'select',
                'label' => true,
                'wrapper_class' => 'form-group',
                'field_class' => 'form-control',
                'inner_label' => false,
                'use_name_attribute' => true,
                'attributes' => [
                ],
                'html_close' => 'select',
            ],

            'file' => [
                'html_open' => 'input',
                'label' => true,
                'wrapper_class' => 'form-group',
                'field_class' => 'form-control',
                'use_name_attribute' => true,
                'attributes' => [
                  'type' => 'file',
                ],
                'html_close' => null,
            ],


            'custom_code' => [
                'html_open' => "div",
                'label' => true,
                'wrapper_class' => null,
                'field_class' => null,
                'inner_label' => false,
                'use_name_attribute' => false,
                'attributes' => [
                ],
                'html_close' => "div",
            ],

            'custom_content' => [
                'html_open' => "div",
                'label' => true,
                'wrapper_class' => null,
                'field_class' => null,
                'inner_label' => false,
                'use_name_attribute' => false,
                'attributes' => [
                ],
                'html_close' => "div",
            ],

        ];

        if($type){
            if(!empty($types[$type])){
                return $types[$type];
            }
        }

        return $types;
    }

    /**
   * Generate form fields types list
   *  @return array
   */
    public function getValidationTypeOptions($value, $formData)
    {

        return [
            'required' => 'joshuatayo.fha::lang.settings.form_field_validation.required',
            'email' => 'joshuatayo.fha::lang.settings.form_field_validation.email',
            'numeric' => 'joshuatayo.fha::lang.settings.form_field_validation.numeric',
            'custom' => 'joshuatayo.fha::lang.settings.form_field_validation.custom',
        ];
    }

    /**
     * @var array Set Attribute
     */
    public function setUrl($pageName, $controller)
    {
        $params = [
            'id' => $this->id,
            'slug' => $this->slug,
        ];

        return $this->url = $controller->pageUrl($pageName, $params);
    }

    /**
     * @var array Scope
     */
    public function scopeIsEnabled($query)
    {
        return $query
            ->where('is_enabled', true)
            ->where('created_at', '<', Carbon::now());
    }

    public function scopeListFrontEnd($query, $options)
    {
        /*
         * Default options
         */
        extract(array_merge([
            'page'          => 1,
            'perPage'       => 30,
            'sort'          => 'created_at',
            'order'         => 'desc',
        ], $options));


        $query->isEnabled();

        if($sort != 'created_at'){
            $sort = 'updated_at';
        }

        if($order != 'desc'){
            $order = 'asc';
        }

        $query->orderBy($sort, $order);


        return $query->paginate($perPage, $page);
    }
}
