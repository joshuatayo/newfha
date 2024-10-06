<?php namespace JoshuaTayo\Report\Models;

use Model;

/**
 * Model
 */
class Report extends Model
{
    use \October\Rain\Database\Traits\Validation;


    /**
     * @var string table in the database used by the model.
     */
    public $table = 'joshuatayo_report_reports';

    /**
     * @var array rules for validation.
     */
    public $rules = [
        'title'    => 'required|unique:joshuatayo_report_reports',
        'slug'     => 'required|unique:joshuatayo_report_reports',
        'document' => 'required'
    ];

    /**
     * @var array Sluggable configuration.
     */
    protected $slugs = [
        'slug' => 'title'
    ];

    public $belongsTo = [
        'category' => [
            'JoshuaTayo\Report\Models\Category',
            'scope' => 'isEnabled',
            'order' => 'name'
        ]
    ];

    /**
     * @var array Get Attribute
     */
    public function getCategoryNameAttribute(){
        if (!$this->category) {
            return;
        }
        return $this->category->name;
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

        $params['category'] = null;

        return $this->url = $controller->pageUrl($pageName, $params);
    }

    /**
     * @var array Sorting Options
     */
    public static $allowedSortingOptions = array(
        'title'         => 'Title',
        'created_at'    => 'Created At',
    );

    /**
     * @var array Scope
     */
    public function scopeIsEnabled($query)
    {
        return $query->where('is_enabled', true);
    }

    public function scopeListFrontEnd($query, $options)
    {
        /*
         * Default options
         */
        extract(array_merge([
            'page'          => null,
            'perPage'       => null,
            'sort'          => 'created_at',
            'order'         => 'desc',
            'category'      => null,
        ], $options));

        $query->isEnabled();

        if(!empty($category)){
            $query->where("category_id", "=", $category);
        }

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
