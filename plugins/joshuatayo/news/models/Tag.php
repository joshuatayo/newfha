<?php namespace JoshuaTayo\News\Models;

use Model;

/**
 * Model
 */
class Tag extends Model
{
    use \October\Rain\Database\Traits\Validation;
    

    /**
     * @var string The database table used by the model.
     */
    public $table = 'joshuatayo_news_tags';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];
}
