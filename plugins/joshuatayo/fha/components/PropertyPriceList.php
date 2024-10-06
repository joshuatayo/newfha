<?php namespace JoshuaTayo\Fha\Components;

use Str;
use Cms\Classes\Page;
use Cms\Classes\ComponentBase;
use JoshuaTayo\Fha\Models\PropertyPrice as PropertyPriceModel;

class PropertyPriceList extends ComponentBase
{
    public  $prices,
            $noPriceMessage,
            $pageParam,
            $itemsPerPage;

    public function componentDetails()
    {
        return [
            'name'        => 'PropertyPriceList',
            'description' => 'Display the list of Properties Price on page'
        ];
    }

    public function defineProperties()
    {
        return [
            'noPriceMessage' => [
                'title'             => 'No Price message',
                'description'       => 'Message to display in the price list in case if there are no price',
                'type'              => 'string',
                'default'           => 'No Price found',
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
        $this->prices                 = $this->page['prices']         = $this->loadList();
    }

    public function prepareVars()
    {
        $this->noPriceMessage       = $this->page['noPriceMessage'] = $this->property('noPriceMessage');
        $this->pageParam            = $this->page['pageParam']    = $this->paramName('pageNumber');
        $this->itemsPerPage         = $this->page['itemsPerPage'] = $this->property('itemsPerPage');
    }

    public function loadList()
    {
        $param          =[
            'page'          => $this->property("pageNumber",1),
            'perPage'       => $this->itemsPerPage,
            'sort'          => $this->property('sortField', 'created_at'),//'created_at',
            'order'         => $this->property('sortType', 'created_at'), //'desc',
        ];

        $prices          = PropertyPriceModel::ListFrontEnd($param);

        $prices->each(function($price) {
            $price->setUrl($this->detailPage, $this->controller);
        });

        return $prices;
    }
}