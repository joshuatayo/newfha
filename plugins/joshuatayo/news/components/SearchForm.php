<?php namespace JoshuaTayo\News\Components;

use Str;
use Lang;
use Redirect;
use Cms\Classes\Page;
use Cms\Classes\ComponentBase;
use JoshuaTayo\News\Models\Post;

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