<?php namespace JoshuaTayo\ProcurementAdvert\Models;

use Model;

/**
 * Model
 */
class Advert extends Model
{
    use \October\Rain\Database\Traits\Validation;


    /**
     * @var string table in the database used by the model.
     */
    public $table = 'joshuatayo_procurementadvert_adverts';

    /**
     * @var array rules for validation.
     */
    public $rules = [
        'title'    => 'required|unique:joshuatayo_procurementadvert_adverts',
        'slug'     => 'required|unique:joshuatayo_procurementadvert_adverts',
        'date_added'     => 'required',
        'deadline'     => 'required',
    ];

    /**
     * @var array Sluggable configuration.
     */
    protected $slugs = [
        'slug' => 'title'
    ];

    public $jsonable = ['documents'];

    public $belongsTo = [
        'category' => [
            'JoshuaTayo\ProcurementAdvert\Models\Category',
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
        return $query
            ->where('is_enabled', true)
            ->where('date_added', '<=', now())
            ->where('deadline', '>=', now());
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
            'search'        => '',
        ], $options));

        $query->isEnabled();

        if(!empty($category)){
            $query->where("category_id", "=", $category);
        }

        $search = trim($search);

        $searchableFields = [
            'title'
        ];

        if (strlen($search)) {
            $query->searchWhere($search, $searchableFields);
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
