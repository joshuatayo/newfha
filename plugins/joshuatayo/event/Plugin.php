<?php namespace JoshuaTayo\Event;

use System\Classes\PluginBase;

/**
 * Plugin class
 */
class Plugin extends PluginBase
{
    /**
     * register method, called when the plugin is first registered.
     */
    public function register()
    {
    }

    /**
     * boot method, called right before the request route.
     */
    public function boot()
    {
    }

    /**
     * registerComponents used by the frontend.
     */
    public function registerComponents()
    {
        return [
            'JoshuaTayo\Event\Components\EventList' => 'eventList',
            'JoshuaTayo\Event\Components\EventDetails'  => 'eventDetails',
            'JoshuaTayo\Event\Components\CategoryList'  => 'eventCategoryList',
            'JoshuaTayo\Event\Components\SearchForm'       => 'searchForm',
        ];
    }

    /**
     * registerSettings used by the backend.
     */
    public function registerSettings()
    {
    }
}
