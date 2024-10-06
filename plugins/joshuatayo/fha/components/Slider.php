<?php namespace JoshuaTayo\Fha\Components;

use Str;
use Cms\Classes\Page;
use Cms\Classes\ComponentBase;
use JoshuaTayo\Fha\Models\Slider as SliderModel;

class Slider extends ComponentBase
{
    public  $data;

    public function componentDetails()
    {
        return [
            'name'        => 'Slider',
            'description' => 'Display Slideshow on page'
        ];
    }

    public function defineProperties()
    {
        return [
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

    public function onRun()
    {
        $this->data                 = $this->page['data']         = $this->loadList();
    }


    public function loadList()
    {
        $param          =[
            'sort'          => $this->property('sortField', 'created_at'),//'created_at',
            'order'         => $this->property('sortType', 'created_at'), //'desc',
        ];

        $items          = SliderModel::where('is_enabled', true)->get();

        $items->each(function($item) {
            $item->setUrl($this->detailPage, $this->controller);
        });

        return $items;
    }
}