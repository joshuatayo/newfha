<?php namespace JoshuaTayo\Fha\Models;

use Model;
use Carbon\Carbon;

/**
 * Model
 */
class Project extends Model
{
    use \October\Rain\Database\Traits\Validation;
    

    /**
     * @var string The database table used by the model.
     */
    public $table = 'joshuatayo_fha_projects';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    /**
     * @var array Relations
     */
    public $attachOne = [
        'bg_image' => ['System\Models\File'],
    ];
    
    public $belongsTo = [
        'submenu' => [
            'JoshuaTayo\Menu\Models\Submenu',
            'conditions' => 'menu_id = 5'
        ],
    ];

    /**
     * @var array Get Attribute
     */
    public function getSrcimageAttribute($value, $w = 360, $h = 370)
    {
        return $this->bg_image->getThumb($w, $h, ['mode' => 'crop']);
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
