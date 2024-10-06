<?php namespace JoshuaTayo\Fha\Components;

use Cms\Classes\ComponentBase;
use JoshuaTayo\Fha\Models\Video as VideoModel;

class VideoList extends ComponentBase
{
    public $videos,
            $noVideoMessage,
            $pageParam,
            $videosPerPage;

    public function componentDetails()
    {
        return [
            'name'        => 'Video List',
            'description' => 'Display video list on page',
        ];
    }

    public function defineProperties()
    {
        return [
            'noVideoMessage' => [
                'title'             => 'No Videos message',
                'description'       => 'Message to display if no video is found',
                'type'              => 'string',
                'default'           => 'No video found',
                'showExternalParam' => false
            ],
            'pageNumber' => [
                'title'             => 'Page number',
                'description'       => 'This value is use to determine what page the video is on',
                'type'              => 'string',
                'default'           => '{{ :page }}',
            ],
            'videosPerPage' => [
                'title'             => 'Videos per page',
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
                    'title'       => 'Title',
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

    public function onRun()
    {
        $this->prepareVars();
        $this->videos = $this->page['videos'] = $this->loadList();
    }

    public function prepareVars()
    {
        $this->noVideoMessage       = $this->page['noVideoMessage']        = $this->property('noVideoMessage');
        $this->pageParam            = $this->page['pageParam']    = $this->paramName('pageNumber');
        $this->videosPerPage         = $this->page['videosPerPage'] = $this->property('videosPerPage');
    }

    public function loadList()
    {
        $param = [
            'page'          => $this->property("pageNumber",1),
            'perPage'       => $this->videosPerPage,
            'sort'          => $this->property('sortField', 'created_at'),//'created_at',
            'order'         => $this->property('sortType', 'desc'), //'desc',
        ];

        $videos = VideoModel::ListFrontEnd($param);

        return $videos;
    }
}
