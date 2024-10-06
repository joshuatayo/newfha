<?php namespace JoshuaTayo\ProcurementAdvert\Components;

use Cms\Classes\ComponentBase;
use Cms\Classes\Page;
use JoshuaTayo\ProcurementAdvert\Models\Advert as AdvertModel;
use JoshuaTayo\ProcurementAdvert\Models\Category as CategoryModel;
use Input;

class AdvertList extends ComponentBase
{

    /**
     * A collection of records to display
     * @var \October\Rain\Database\Collection
     */
    public $adverts,
            $noAdvertMessage,
            $displayType,
            $detailPage,
            $pageParam,
            $advertsPerPage,
            $categoryId,
            $searchFilter;

    public function componentDetails()
    {
        return [
            'name'        => 'Advert List',
            'description' => 'Display list of adverts'
        ];
    }

    public function defineProperties()
    {
        return [
            'noAdvertMessage' => [
                'title'             => 'No adverts message',
                'description'       => 'Message to display if no adverts',
                'type'              => 'string',
                'default'           => 'No adverts found',
                'showExternalParam' => false
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
            'advertsPerPage' => [
                'title'             => 'Adverts per page',
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
                'title'             => 'Advert details page',
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
                    'name'       => 'Name',
                    'created_at' => 'Created At',
                    'updated_at' => 'Updated At'
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

    public function getPageOptions()
    {
        return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }

    public function onRun()
    {
        $this->prepareVars();
        $this->adverts                 = $this->page['adverts']         = $this->loadList();
    }

    public function prepareVars()
    {
        $this->displayType = $this->page['displayType'] = $this->property('displayType');
        $this->noAdvertMessage = $this->page['noAdvertMessage'] = $this->property('noAdvertMessage');
        $this->pageParam = $this->page['pageParam'] = $this->paramName('pageNumber');
        $this->advertsPerPage         = $this->page['advertsPerPage'] = $this->property('advertsPerPage');
        if($this->property("categoryFilter", null))
        {
            $this->prepareCategory();
        }

        $this->categoryPage         = $this->page['categoryPage'] = $this->property('categoryPage');
        $this->detailPage           = $this->page['detailPage']   = $this->property('detailPage');
        $this->searchFilter         = $this->page['searchFilter'] = trim(input('search'));
    }

    protected function prepareCategory()
    {
        $category = $this->property("categoryFilter");

        if(!is_numeric($category))
        {
            $categoryRow = CategoryModel::where("slug", "=", $category)->first();
        }
        else
        {
            $categoryRow = CategoryModel::where("id", "=", $category)->first();
        }
        if(!empty($categoryRow->id))
        {
            $this->page['category'] = $categoryRow;
            $this->categoryId       = $this->page['categoryId'] = $categoryRow->id;
        }
        else
        {
            $this->categoryId       = $this->page['categoryId'] = null;
        }
    }

    public function loadList()
    {
        $param = [
            'page'          => null,
            'perPage'       => null,
            'sort'          => $this->property('sortField', 'created_at'),//'created_at',
            'order'         => $this->property('sortType', 'desc'), //'desc',
            'category'      => Input::get('category', $this->categoryId),
            'search'        => $this->searchFilter,
        ];

        $adverts = AdvertModel::ListFrontEnd($param);

        $adverts->each(function($advert) {
            $advert->setUrl($this->detailPage, $this->controller);
            if(!empty($advert->category))
            {
                $advert->category->setUrl($this->categoryPage, $this->controller);
            }
        });

        return $adverts;
    }
}
