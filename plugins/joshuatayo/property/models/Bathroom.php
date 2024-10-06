<?php namespace JoshuaTayo\Property\Models;

use Model;

/**
 * Model
 */
class Bathroom extends Model
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
    public $table = 'joshuatayo_property_bathrooms';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    /**
     * @var array Relation
     */
    public $hasMany = [
        'property' => ['JoshuaTayo\Property\Models\Property']
    ];
}
