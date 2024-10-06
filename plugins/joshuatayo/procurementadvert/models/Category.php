<?php namespace JoshuaTayo\ProcurementAdvert\Models;

use Model;

/**
 * Model
 */
class Category extends Model
{
    use \October\Rain\Database\Traits\Validation;


    /**
     * @var string table in the database used by the model.
     */
    public $table = 'joshuatayo_procurementadvert_categories';

    /**
     * @var array Validation rules
     */
    public $rules = [
        'name'     => 'required',
        'slug'      => ['required', 'regex:/^[a-z0-9\/\:_\-\*\[\]\+\?\|]*$/i', 'unique:joshuatayo_procurementadvert_categories']
    ];

    public $hasMany = [
        'adverts' => \JoshuaTayo\ProcurementAdvert\Models\Advert::class
    ];

    /**
     * @var array Scope
     */
    public function scopeIsEnabled($query)
    {
        return $query->where('is_enabled', true);
    }

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

    public function getAdvertsCountAttribute(){
        return $this->adverts->count();
    }

}
