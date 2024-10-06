<?php namespace JoshuaTayo\Menu\Models;

use Model;
use Carbon\Carbon;
use Cms\Classes\Page;

/**
 * Model
 */
class Menu extends Model
{
    use \October\Rain\Database\Traits\Validation;
    

    /**
     * @var string The database table used by the model.
     */
    public $table = 'joshuatayo_menu_menus';

    /**
     * @var array Validation rules
     */
    public $rules = [
        'name'      => ['required', 'unique:joshuatayo_menu_menus'],
        'sorting'      => ['required', 'unique:joshuatayo_menu_menus'],
    ];

    /**
     * @var array Relations
     */
    public $hasMany = [
        'submenus' => [
            'JoshuaTayo\Menu\Models\Submenu',
            
        ],
    ];

    public function getUrlOptions()
    {
        return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
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
}
