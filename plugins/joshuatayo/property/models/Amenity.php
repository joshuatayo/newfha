<?php namespace JoshuaTayo\Property\Models;

use Model;

/**
 * Model
 */
class Amenity extends Model
{
    use \October\Rain\Database\Traits\Validation;
    

    /**
     * @var string The database table used by the model.
     */
    public $table = 'joshuatayo_property_amenities';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];
}
