<?php namespace JoshuaTayo\Fha\Components;

use Str;
use Cms\Classes\Page;
use Cms\Classes\ComponentBase;
use JoshuaTayo\Fha\Models\Gallery as GalleryModel;

class GalleryList extends ComponentBase
{
    public  $photos,
            $noPhotoMessage,
            $detailPage,
            $pageParam,
            $itemsPerPage;

    public function componentDetails()
    {
        return [
            'name'        => 'GalleryList',
            'description' => 'Display the list of Gallery on page'
        ];
    }

    public function defineProperties()
    {
        return [
            'noPhotoMessage' => [
                'title'             => 'No Photo message',
                'description'       => 'Message to display in the photo list in case if there are no photo',
                'type'              => 'string',
                'default'           => 'No Photo found',
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
            'sortField' => [
                'title'             => 'Sort field',
                'type'              => 'dropdown',
                'default'           => 'created_at',
                'group'             => 'Ranking',
                'options'           => [
                    'date'       => 'Date',
                    'created_at' => 'Created',
                    'updated_at' => 'Updated',
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
        $this->photos    = $this->page['photos']  = $this->loadList();
    }

    public function prepareVars()
    {
        $this->noPhotoMessage       = $this->page['noPhotoMessage']        = $this->property('noPhotoMessage');
        $this->pageParam            = $this->page['pageParam']    = $this->paramName('pageNumber');
        $this->itemsPerPage         = $this->page['itemsPerPage'] = $this->property('itemsPerPage');
        $this->detailPage           = $this->page['detailPage']   = $this->property('detailPage');
    }

    public function loadList()
    {
        $param          =[
            'page'          => $this->property("pageNumber",1),
            'perPage'       => $this->itemsPerPage,
            'sort'          => $this->property('sortField', 'created_at'),//'created_at',
            'order'         => $this->property('sortType', 'created_at'), //'desc',
        ];

        $photos       = GalleryModel::ListFrontEnd($param);

        $photos->each(function($photo) {
            $photo->setUrl($this->detailPage, $this->controller);
        });

        return $photos;
    }
}