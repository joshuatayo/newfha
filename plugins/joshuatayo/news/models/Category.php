<?php namespace JoshuaTayo\News\Models;

use Model;

/**
 * Model
 */
class Category extends Model
{
    use \October\Rain\Database\Traits\Validation;
    

    /**
     * @var string The database table used by the model.
     */
    public $table = 'joshuatayo_news_categories';

    /**
     * @var array Validation rules
     */
    public $rules = [
        'name'     => 'required',
        'slug'      => ['required', 'regex:/^[a-z0-9\/\:_\-\*\[\]\+\?\|]*$/i', 'unique:joshuatayo_news_categories']
    ];

    public $hasMany = [
        'posts' => ['JoshuaTayo\News\Models\Post']
    ];


    public function setUrl($pageName, $controller)
    {
        $params = [
            'id' => $this->id,
            'slug' => $this->slug,
        ];

        return $this->url = $controller->pageUrl($pageName, $params);
    }

    public function getCountPostsAttribute(){
        return $this->posts->count();
    }
}
