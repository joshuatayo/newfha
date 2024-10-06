<?php namespace JoshuaTayo\News\Models;

use Db;
use Str;
use Url;
use Model;
use Carbon\Carbon;
/**
 * Model
 */
class Post extends Model
{
    use \October\Rain\Database\Traits\Validation;
    

    /**
     * @var string The database table used by the model.
     */
    public $table = 'joshuatayo_news_posts';

    /**
     * @var array Validation rules
     */
    public $rules = [
        'title'    => 'required',
        'slug'     => ['regex:/^[a-z0-9\/\:_\-\*\[\]\+\?\|]*$/i', 'unique:joshuatayo_news_posts'],
    ];

    protected $slugs = [
        'slug' => 'title'
    ];

    /**
     * @var array Sorting Options
     */
    public static $allowedSortingOptions = array(
        'title'         => 'title',
        'created_at'    => 'Created',
        'published_at'    => 'Published',
        'views'         => 'Views'
    );

    public $attachOne = [
        'post_image' => ['System\Models\File'],
    ];

    public $belongsTo = [
        'category' => [
            'JoshuaTayo\News\Models\Category',
            'order' => 'name'
        ],
        'user' => ['Backend\Models\User']
    ];

    public $belongsToMany = [
        'tags' => [
            'JoshuaTayo\News\Models\Tag',
            'table' => 'joshuatayo_news_posts_tags',
            'order' => 'name'
        ],
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
     * List of administrators
     */
    public function getUserOptions()
    {
        $result = [0 => '-- select user --'];
        $users = Db::table('backend_users')->orderBy('login', 'asc')->get()->all();

        foreach ($users as $user) {
            $name = trim($user->first_name.' '.$user->last_name);
            $name = ($name != '') ? ' ('.$name.')' : '';
            $result[$user->id] = $user->login.$name;
        }

        return $result;
    }

    /**
     * Check value of some fields
     */
    public function beforeSave()
    {
        if (!isset($this->slug) || empty($this->slug)) {
            $this->slug = Str::slug($this->title);
        }

        if (!isset($this->category_id) || empty($this->category_id)) {
            $this->category_id = 0;
        }

        if (!isset($this->user_id) || empty($this->user_id)) {
            $this->user_id = 0;
        }

        if ($this->published == 1 && empty($this->published_at)) {
            $this->published_at = Carbon::now();
        }

        if (!isset($this->views) || empty($this->views)) {
            $this->views = 0;
        }
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

        $params['tag'] = null;

        return $this->url = $controller->pageUrl($pageName, $params);
    }

    /**
     * @var array Scope
     */

    public function scopeIsPublished($query)
    {
        return $query
            ->where('published', true)
            ->where('created_at', '<', Carbon::now());
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
            'title'          => null,
            'category'          => null,
            'tag'         => null,
            'search'          => '',
        ], $options));


        $query->isPublished();

        $searchableFields = [
            'title'
        ];

        if(!empty($category)){
            $query->where("category_id", "=", $category);
        }

        if(!empty($tag)){
            $query->where("tag_id", "=", $tag);
        }

        $search = trim($search);

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
            'attribute' => 'published_at'
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
        return self::isPublished()->applySibling()->first();
    }

    /**
     * Returns the previous post, if available.
     *
     * @return self
     */
    public function prev()
    {
        return self::isPublished()->applySibling(-1)->first();
    }

}
