<?php namespace JoshuaTayo\ProcurementAdvert;

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
            'JoshuaTayo\ProcurementAdvert\Components\AdvertList' => 'advertList',
            'JoshuaTayo\ProcurementAdvert\Components\AdvertDetails'  => 'advertDetails',
            'JoshuaTayo\ProcurementAdvert\Components\CategoryList'  => 'advertCategoryList',
            'JoshuaTayo\ProcurementAdvert\Components\SearchForm'       => 'advertSearchForm',
        ];
    }

    /**
     * registerSettings used by the backend.
     */
    public function registerSettings()
    {
    }
}
