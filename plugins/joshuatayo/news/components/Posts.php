<?php namespace JoshuaTayo\News\Components;

use Str;
use Redirect;
use Cms\Classes\Page;
use Cms\Classes\ComponentBase;
use JoshuaTayo\News\Models\Post as PostModel;
use JoshuaTayo\News\Models\Category;
use Input;
use October\Rain\Exception\ApplicationException;

class Posts extends ComponentBase
{
	public  $posts,
            $noPostMessage,
            $displayType,
            $categoryPage,
            $detailPage,
            $searchPage,
            $pageParam,
            $postsPerPage,
            $category,
            $searchFilter;

    public function componentDetails()
    {
        return [
            'name'        => 'Posts',
            'description' => 'Display posts'
        ];
    }

    public function defineProperties()
    {
        return [
            'noPostMessage' => [
                'title'             => 'No Post message',
                'description'       => 'Message to display in the post list in case if there are no post',
                'type'              => 'string',
                'default'           => 'No post found',
                'showExternalParam' => false
            ],
            'displayType' => [
                'title'             => 'Display Type',
                'type'              => 'dropdown',
                'default'           => '',
                'options'           => $this->getDisplayTypeOptions()
            ],
            'pageNumber' => [
                'title'             => 'Page number',
                'description'       => 'This value is use to determine what page the user is on',
                'type'              => 'string',
                'default'           => '{{ :page }}',
            ],
            'categoryFilter' => [
                'title'             => 'Category filter',
                'description'       => 'Enter a Category slug',
                'type'              => 'string',
                'default'           => '{{ :slug }}'
            ],
            'postsPerPage' => [
                'title'             => 'Posts per page',
                'type'              => 'string',
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => 'Only Integer',
                'default'           => 15,
            ],
            'categoryPage' => [
                'title'             => 'Category page',
                'type'              => 'dropdown',
                'group'             => 'Links',
                'options'           => $this->getPageOptions()
            ],
            'detailPage' => [
                'title'             => 'Post page',
                'type'              => 'dropdown',
                'group'             => 'Links',
                'options'           => $this->getPageOptions()
            ],
            'sortField' => [
                'title'             => 'Sort field',
                'type'              => 'dropdown',
                'default'           => 'created_at',
                'group'             => 'Ranking',
                'options'           => [
                    'created_at' => 'Created',
                    'updated_at' => 'Updated',
                    'published_at' => 'Published At'
                ],
            ],
            'sortType' => [
                'title'             => 'Sort type',
                'type'              => 'dropdown',
                'default'           => 'desc',
                'group'             => 'Ranking',
                'options'           => [
                    'desc' => 'Descending',
                    'asc'  => 'Ascending'
                ],
            ],
        ];
    }

    public function getDisplayTypeOptions()
    {
        return [
            ''              => 'default',
            'listPost'      => 'List Post',
            'latestPost'    => 'Latest Post',
            'sidePost'      => 'Side Post',
        ];
    }

    public function getPageOptions()
    {
        return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }

    public function onRun()
    {
        $this->prepareVars();
        $this->posts                 = $this->page['posts']         = $this->loadList();
    }

    public function prepareVars()
    {
        $this->displayType          = $this->page['displayType']    = $this->property('displayType');
        $this->noPostMessage        = $this->page['noPostMessage']    = $this->property('noPostMessage');
        $this->pageParam            = $this->page['pageParam']    = $this->paramName('pageNumber');
        $this->postsPerPage         = $this->page['postsPerPage'] = $this->property('postsPerPage');

        if($this->property("categoryFilter", null))
        {
            $this->prepareCategory();
        }

        $this->categoryPage         = $this->page['categoryPage'] = $this->property('categoryPage');
        $this->detailPage           = $this->page['detailPage']   = $this->property('detailPage');
        $this->searchFilter = $this->page['searchFilter'] = trim(input('search'));
    }

    protected function prepareCategory()
    {
        $category = $this->property("categoryFilter");
        if(!is_numeric($category))
        {
            $categoryRow = Category::where("slug", "=", $category)->first();
        }
        else
        {
            $categoryRow = Category::where("id", "=", $category)->first();
        }
        if(!empty($categoryRow->id))
        {
            $this->page['category'] = $categoryRow;
            $this->categoryID       = $this->page['categoryID'] = $categoryRow->id;
        }
        else
        {
            $this->categoryID       = $this->page['categoryID'] = null;
        }
    }

    public function loadList()
    {
        $param          =[
            'page'          => $this->property("pageNumber",1),
            'perPage'       => $this->postsPerPage,
            'sort'          => $this->property('sortField', 'created_at'),//'created_at',
            'order'         => $this->property('sortType', 'created_at'), //'desc',
            'category'      => Input::get('category', $this->category),
            'search'        => $this->searchFilter,
        ];

        $posts          = PostModel::ListFrontEnd($param);

        $posts->each(function($post) {
            $post->setUrl($this->detailPage, $this->controller);
        });

        return $posts;
    }

}
