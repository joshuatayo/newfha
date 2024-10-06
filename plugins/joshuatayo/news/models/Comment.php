<?php namespace JoshuaTayo\News\Models;

use Model;

/**
 * Model
 */
class Comment extends Model
{
    use \October\Rain\Database\Traits\Validation;
    

    /**
     * @var string The database table used by the model.
     */
    public $table = 'joshuatayo_news_comments';

    /**
     * @var array Validation rules
     */
    public $rules = [
        'name' => 'required',
        'content' => 'required|min:2|max:500'
    ];
}
