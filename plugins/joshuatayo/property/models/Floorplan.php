<?php namespace JoshuaTayo\Property\Models;

use Model;

/**
 * Model
 */
class Floorplan extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    /*
     * Disable timestamps by default.
     * Remove this line if timestamps are defined in the database table.
     */
    public $timestamps = false;


    /**
     * @var string The database table used by the model.
     */
    public $table = 'joshuatayo_property_floorplans';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    /**
     * @var array Relation
     */
    public $attachOne = [
        'floor_plan_image' => ['System\Models\File'],
    ];
     
    public $belongsTo = [
        'property' => ['JoshuaTayo\Property\Models\Property'],
    ];

    /**
     * @var array Get Attribute
     */
    public function getSrcimageAttribute($value, $w = 619, $h = 465)
    {
        return $this->floor_plan_image->getThumb($w, $h, ['mode' => 'crop']);
    }
}
