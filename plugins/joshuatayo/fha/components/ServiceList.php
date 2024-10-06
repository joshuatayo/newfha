<?php namespace JoshuaTayo\Fha\Components;

use Str;
use Cms\Classes\Page;
use Cms\Classes\ComponentBase;
use JoshuaTayo\Fha\Models\Service as ServiceModel;

class ServiceList extends ComponentBase
{
    public  $services,
            $noServiceMessage,
            $detailPage,
            $searchPage,
            $pageParam,
            $itemsPerPage;

    public function componentDetails()
    {
        return [
            'name'        => 'ServiceList',
            'description' => 'Display the list of Services on page'
        ];
    }

    public function defineProperties()
    {
        return [
            'noServiceMessage' => [
                'title'             => 'No Service message',
                'description'       => 'Message to display in the service list in case if there are no service',
                'type'              => 'string',
                'default'           => 'No Service found',
                'showExternalParam' => false
            ],
            'pageNumber' => [
                'title'             => 'Page number',
                'description'       => 'This value is use to determine what page the user is on',
                'type'              => 'string',
                'default'           => '{{ :page }}',
            ],
            'itemsPerPage' => [
                'title'             => 'Items per page',
                'type'              => 'string',
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => 'Only Integer',
                'default'           => 10,
            ],
            'detailPage' => [
                'title'             => 'Post page',
                'type'              => 'dropdown',
                'group'             => 'Links',
                'options'           => $this->getPageOptions()
            ],
            'searchPage' => [
                'title'             => 'Search page',
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
                    'updated_at' => 'Updated'
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
        $this->services                 = $this->page['services']         = $this->loadList();
    }

    public function prepareVars()
    {
        $this->noServiceMessage     = $this->page['noServiceMessage']    = $this->property('noServiceMessage');
        $this->pageParam            = $this->page['pageParam']    = $this->paramName('pageNumber');
        $this->itemsPerPage         = $this->page['itemsPerPage'] = $this->property('itemsPerPage');
        $this->detailPage           = $this->page['detailPage']   = $this->property('detailPage');
        $this->searchPage           = $this->page['searchPage']   = $this->property('searchPage');
    }

    public function loadList()
    {
        $param          =[
            'page'          => $this->property("pageNumber",1),
            'perPage'       => $this->itemsPerPage,
            'sort'          => $this->property('sortField', 'created_at'),//'created_at',
            'order'         => $this->property('sortType', 'created_at'), //'desc',
        ];

        $services          = ServiceModel::ListFrontEnd($param);

        $services->each(function($service) {
            $service->setUrl($this->detailPage, $this->controller);
        });

        return $services;
    }
}