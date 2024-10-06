<?php namespace JoshuaTayo\Property\Components;

use Str;
use Cms\Classes\Page;
use Cms\Classes\ComponentBase;
use JoshuaTayo\Property\Models\Property as PropertyModel;

class FeatureProperty extends ComponentBase
{
    public  $properties,
            $detailPage,
            $pageParam,
            $itemsPerPage;

    public function componentDetails()
    {
        return [
            'name'        => 'FeatureProperty',
            'description' => 'Display the list of Feature Properties on page'
        ];
    }

    public function defineProperties()
    {
        return [
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
        $this->properties                 = $this->page['properties']         = $this->loadList();
    }

    public function prepareVars()
    {
        $this->detailPage           = $this->page['detailPage']   = $this->property('detailPage');
    }

    public function loadList()
    {

        $properties        = PropertyModel::isFeatured()->get();

        return $properties;
    }
}
