<?php namespace JoshuaTayo\News;

use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public function registerComponents()
    {
    	return [
            'JoshuaTayo\News\Components\Posts'       => 'posts',
            'JoshuaTayo\News\Components\Post'       => 'post',
            'JoshuaTayo\News\Components\Comments'       => 'comments',
            'JoshuaTayo\News\Components\SearchForm'       => 'searchform',
        ];
    }

    public function registerSettings()
    {
    }
}
