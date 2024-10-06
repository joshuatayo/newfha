<?php namespace JoshuaTayo\Report\Components;

use Cms\Classes\ComponentBase;
use Cms\Classes\Page;
use JoshuaTayo\Report\Models\Report as ReportModel;
use JoshuaTayo\Report\Models\Category as CategoryModel;
use Input;

class ReportList extends ComponentBase
{

    /**
     * A collection of records to display
     * @var \October\Rain\Database\Collection
     */
    public $reports,
            $noReportMessage,
            $displayType,
            $detailPage,
            $pageParam,
            $reportsPerPage,
            $categoryId;

    public function componentDetails()
    {
        return [
            'name'        => 'Report List',
            'description' => 'Display list of Report'
        ];
    }

    public function defineProperties()
    {
        return [
            'noReportMessage' => [
                'title'             => 'No reports message',
                'description'       => 'Message to display if no reports',
                'type'              => 'string',
                'default'           => 'No reports found',
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
            'reportsPerPage' => [
                'title'             => 'Reports per page',
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
                'title'             => 'Reports details page',
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
        $this->reports                 = $this->page['reports']         = $this->loadList();
    }

    public function prepareVars()
    {
        $this->displayType = $this->page['displayType'] = $this->property('displayType');
        $this->noReportMessage = $this->page['noReportMessage'] = $this->property('noReportMessage');
        $this->pageParam = $this->page['pageParam'] = $this->paramName('pageNumber');
        $this->reportsPerPage         = $this->page['reportsPerPage'] = $this->property('reportsPerPage');
        if($this->property("categoryFilter", null))
        {
            $this->prepareCategory();
        }

        $this->categoryPage         = $this->page['categoryPage'] = $this->property('categoryPage');
        $this->detailPage           = $this->page['detailPage']   = $this->property('detailPage');
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
        ];

        $reports = ReportModel::ListFrontEnd($param);

        $reports->each(function($report) {
            $report->setUrl($this->detailPage, $this->controller);
            if(!empty($report->category))
            {
                $report->category->setUrl($this->categoryPage, $this->controller);
            }
        });

        return $reports;
    }
}
