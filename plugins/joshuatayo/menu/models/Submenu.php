<?php namespace JoshuaTayo\Menu\Models;

use Model;
use Cms\Classes\Page;

/**
 * Model
 */
class Submenu extends Model
{
    use \October\Rain\Database\Traits\Validation;
    

    /**
     * @var string The database table used by the model.
     */
    public $table = 'joshuatayo_menu_submenus';

    /**
     * @var array Validation rules
     */
    public $rules = [
        'name'      => ['required', 'unique:joshuatayo_menu_submenus'],
    ];

    public function getUrlsAttribute()
    {
        $project = 'projects/';
        $url = $this->url;

        $urlpro = str_finish($project, $url);
        return $urlpro;
    }

    /**
     * @var array Relation
     */
    public $belongsTo = [
        'menu' => ['JoshuaTayo\Menu\Models\Menu'],
    ];
}
