<?php namespace JoshuaTayo\Fha\Models;

use Model;

/**
 * Model
 */
class Video extends Model
{
    use \October\Rain\Database\Traits\Validation;


    /**
     * @var string table in the database used by the model.
     */
    public $table = 'joshuatayo_fha_videos';

    /**
     * @var array rules for validation.
     */
    public $rules = [
        'title'    => 'required|unique:joshuatayo_fha_videos',
        'slug'    => 'required|unique:joshuatayo_fha_videos',
        'link' => 'required',
    ];

    protected $slugs = [
        'slug' => 'title'
    ];

    /**
     * @var array Sorting Options
     */
    public static $allowedSortingOptions = array(
        'title'         => 'Title',
        'date'          => 'Date',
        'created_at'    => 'Created',
        'updated_at'    => 'Updated'
    );

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
     * @var array Scope
     */
    public function scopeIsEnabled($query)
    {
        return $query->where('is_enabled', true);
    }

    /**
     * @var array Scope
     */
    public function scopeListFrontEnd($query, $options)
    {
        /*
         * Default options
         */
        extract(array_merge([
            'page'          => 1,
            'perPage'       => 30,
            'sort'          => 'created_at',
            'order'         => 'desc',
        ], $options));


        $query->isEnabled();

        if(!in_array($sort, self::$allowedSortingOptions)){
            $sort = 'created_at';
        }

        if($order != 'desc'){
            $order = 'asc';
        }

        $query->orderBy($sort, $order);

        return $query->paginate($perPage, $page);
    }

}
