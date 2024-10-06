<?php namespace JoshuaTayo\Event\Models;

use Model;

/**
 * Model
 */
class Event extends Model
{
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var string table in the database used by the model.
     */
    public $table = 'joshuatayo_event_events';

    /**
     * @var array rules for validation.
     */
    public $rules = [
        'title'    => 'required|unique:joshuatayo_event_events',
        'slug'     => 'required|unique:joshuatayo_event_events',
        'image' => 'required',
        'date'  => 'required',
        'description' => 'required',
    ];

    public $jsonable = ['contact_info','additional_info'];

    /**
     * @var array Sluggable configuration.
     */
    protected $slugs = [
        'slug' => 'title'
    ];

    /**
     * @var array Sorting Options
     */
    public static $allowedSortingOptions = array(
        'title'         => 'Title',
        'date'         => 'Date',
        'created_at'    => 'Created At',
    );

    public $belongsTo = [
        'category' => [
            'JoshuaTayo\Event\Models\Category',
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
            'page'          => 1,
            'perPage'       => 30,
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


    // Next and previous post
    /**
     * Apply a constraint to the query to find the nearest sibling
     *
     * @param       $query
     * @param array $options
     */
    public function scopeApplySibling($query, $options = [])
    {
        if (!is_array($options)) {
            $options = ['direction' => $options];
        }

        extract(array_merge([
            'direction' => 'next',
            'attribute' => 'date'
        ], $options));

        $isPrevious = in_array($direction, ['previous', -1]);
        $directionOrder = $isPrevious ? 'desc' : 'asc';
        $directionOperator = $isPrevious ? '<' : '>';

        return $query
        ->where('id', '<>', $this->id)
        ->where($attribute, $directionOperator, $this->$attribute)
        ->orderBy($attribute, $directionOrder);
    }

    /**
     * Returns the next post, if available.
     *
     * @return self
     */
    public function next()
    {
        return self::isEnabled()->applySibling()->first();
    }

    /**
     * Returns the previous post, if available.
     *
     * @return self
     */
    public function prev()
    {
        return self::isEnabled()->applySibling(-1)->first();
    }
}
