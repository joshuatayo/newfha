<?php namespace JoshuaTayo\Property\Components;

use Str;
use Cms\Classes\Page;
use Cms\Classes\ComponentBase;
use Input;
use JoshuaTayo\Property\Models\Property as PropertyModel;

class PropertyList extends ComponentBase
{
    public  $data,
            $noPropertyMessage,
            $displayType,
            $detailPage,
            $searchPage,
            $pageParam,
            $itemsPerPage,

            $typeId,
            $statusId,
            $bedroomId,
            $bathroomId,
            $stateId,
            $cityId,
            $placeId;

    public function componentDetails()
    {
        return [
            'name'        => 'PropertyList',
            'description' => 'Display the list of Properties on page'
        ];
    }

    public function defineProperties()
    {
        return [
            'noPropertyMessage' => [
                'title'             => 'No Property message',
                'description'       => 'Message to display in the property list in case if there are no property',
                'type'              => 'string',
                'default'           => 'No properties found',
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

    public function getDisplayTypeOptions()
    {
        return [
            ''                   => 'default',
            'listProperty'       => 'List Property',
            'newProperty'        => 'New Property',
            'featureProperty'    => 'Feature Property',
        ];
    }

    public function getPageOptions()
    {
        return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }

    public function onRun()
    {
        $this->prepareVars();
        $this->data                 = $this->page['data']         = $this->loadList();
    }

    public function prepareVars()
    {
        $this->displayType    = $this->page['displayType']    = $this->property('displayType');
        $this->noPropertyMessage    = $this->page['noPropertyMessage']    = $this->property('noPropertyMessage');
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
            'title'         => Input::get('title', null),
            'type'          => Input::get('type', $this->typeId),
            'status'        => Input::get('status', $this->stateId),
            'bedroom'       => Input::get('bedroom', $this->bedroomId),
            'bathroom'     => Input::get('bathroom', $this->bathroomId),
            'state'         => Input::get('state', $this->stateId),
            'city'          => Input::get('city', $this->cityId),
            'place'         => Input::get('place', $this->placeId),
            'amenities'     => Input::get('amenities', null),
            'price'         => Input::get('price', null)
        ];

        $items          = PropertyModel::ListFrontEnd($param);

        $items->each(function($item) {
            $item->setUrl($this->detailPage, $this->controller);
        });

        return $items;
    }
}
