<?php namespace JoshuaTayo\Property\Models;

use Model;

/**
 * Model
 */
class Type extends Model
{
    use \October\Rain\Database\Traits\Validation;
    

    /**
     * @var string The database table used by the model.
     */
    public $table = 'joshuatayo_property_types';

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
