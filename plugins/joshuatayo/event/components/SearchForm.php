<?php namespace JoshuaTayo\Event\Components;

use Cms\Classes\Page;
use Cms\Classes\ComponentBase;

class SearchForm extends ComponentBase
{
    public  $searchPage,
            $displayType;

    public function componentDetails()
    {
        return [
            'name'        => 'Search Form',
            'description' => 'Display search form on web page'
        ];
    }

    public function defineProperties()
    {
        return [
            'searchPage' => [
                'title'             => 'Search page',
                'type'              => 'dropdown',
                'options'           => $this->getPageOptions(),
                'group'             => 'Links'
            ],
        ];
    }

    public function getPageOptions()
    {
        return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }

    public function init()
    {
        $this->page['searchPage']   =
        $this->searchPage           = $this->property('searchPage');
    }
}
