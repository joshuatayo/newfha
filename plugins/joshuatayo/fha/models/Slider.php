<?php namespace JoshuaTayo\Fha\Models;

use Model;
use Carbon\Carbon;

/**
 * Model
 */
class Slider extends Model
{
    use \October\Rain\Database\Traits\Validation;
    

    /**
     * @var string The database table used by the model.
     */
    public $table = 'joshuatayo_fha_sliders';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    /**
     * @var array Relation
     */
    public $attachOne = [
        'slider_image' => ['System\Models\File'],
    ];

    /**
     * @var array Set Attribute
     */
    public function setUrl($pageName, $controller)
    {
        $params = [
            'id' => $this->id,
            'slug' => $this->slug,
        ];

        return $this->url = $controller->pageUrl($pageName, $params);
    }

    /**
     * @var array Get Attribute
     */
    public function getSrcimageAttribute($value, $w = 368, $h = 287)
    {
        foreach ($this->gallery_images as $image) {
            return $image->getThumb($w, $h, ['mode' => 'crop']);
        }
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

    public function scopeListFrontEnd($query, $options)
    {
        /*
         * Default options
         */
        extract(array_merge([
            'sort'          => 'created_at',
            'order'         => 'desc',
        ], $options));


        $query->isEnabled();

        if($sort != 'created_at'){
            $sort = 'updated_at';
        }

        if($order != 'desc'){
            $order = 'asc';
        }

        $query->orderBy($sort, $order);


        return $query->orderBy($sort, $order);
    }
}
