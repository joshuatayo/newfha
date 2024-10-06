<?php namespace JoshuaTayo\Fha\Models;

use Str;
use Twig;
use Model;
use JoshuaTayo\Fha\Models\Service;

/**
 * Model
 */
class ServiceForm extends Model
{
    use \October\Rain\Database\Traits\Validation;
    

    /**
     * @var string The database table used by the model.
     */
    public $table = 'joshuatayo_fha_serviceforms';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    protected $jsonable = ['form_data'];

    /**
     * @var array Relation
     */
    public $attachMany = [
        'service_files' => ['System\Models\File'],
    ];

    public $belongsTo = [
        'service' => ['JoshuaTayo\Fha\Models\Service'],
    ];

    /**
     * @var array Get Attribute
     */
    public function getServiceNameAttribute(){
        if (!$this->service) {
            return;
        }
        return $this->service->title;
    }


    public function storeFormData($data, $file, $slug){
        $slug = $slug;
        $service = Service::where('slug', $slug)->first();
        
        $output = [];
        $formFields = $service['form_fields'];

        foreach($data as $key => $value) {
            $fieldDefined = null;
            foreach( $formFields as $field) {
                if( $field['name'] == $key) {
                    $fieldDefined = true;
                }
            }
            $output[$key] = e($value['value']);
        }

        $this->form_data = $output;
        $this->service_files = $file;
        $service_id = $service['id'];
        $this->service_id = $service_id;
        $form_id = Str::random(10);
        $this->form_id = $form_id;
        $this->is_new = 1;
        $this->is_reply = 0;
        $this->save();

    }
}
