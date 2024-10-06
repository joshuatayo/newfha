<?php namespace JoshuaTayo\Event\Components;

use Cms\Classes\ComponentBase;
use Cms\Classes\Page;
use JoshuaTayo\Event\Models\Event as EventModel;
use JoshuaTayo\Event\Models\Category as CategoryModel;
use Input;

class EventList extends ComponentBase
{

    /**
     * A collection of records to display
     * @var \October\Rain\Database\Collection
     */
    public $events,
            $noEventMessage,
            $displayType,
            $detailPage,
            $pageParam,
            $eventsPerPage,
            $categoryId,
            $searchFilter;

    public function componentDetails()
    {
        return [
            'name'        => 'Event List',
            'description' => 'Display list of events'
        ];
    }

    public function defineProperties()
    {
        return [
            'noEventMessage' => [
                'title'             => 'No events message',
                'description'       => 'Message to display if no events',
                'type'              => 'string',
                'default'           => 'No events found',
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
            'eventsPerPage' => [
                'title'             => 'Events per page',
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
                'title'             => 'Event details page',
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
                    'created_at' => 'Created At',
                    'date' => 'Event Date',
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
        $this->events                 = $this->page['events']         = $this->loadList();
    }

    public function prepareVars()
    {
        $this->displayType = $this->page['displayType'] = $this->property('displayType');
        $this->noEventMessage = $this->page['noEventMessage'] = $this->property('noEventMessage');
        $this->pageParam = $this->page['pageParam'] = $this->paramName('pageNumber');
        $this->eventsPerPage         = $this->page['eventsPerPage'] = $this->property('eventsPerPage');
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
            'page'          => $this->property("pageNumber",1),
            'perPage'       => $this->eventsPerPage,
            'sort'          => $this->property('sortField', 'created_at'),//'created_at',
            'order'         => $this->property('sortType', 'desc'), //'desc',
            'category'      => Input::get('category', $this->categoryId),
            'search'        => $this->searchFilter,
        ];

        $events = EventModel::ListFrontEnd($param);

        $events->each(function($event) {
            $event->setUrl($this->detailPage, $this->controller);
            if($event->category) {
                $event->category->setUrl($this->categoryPage, $this->controller);
            }
        });

        return $events;
    }
}
