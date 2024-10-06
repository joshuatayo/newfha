<?php namespace JoshuaTayo\Menu;

use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public function registerComponents()
    {
    	return [
            '\JoshuaTayo\Menu\Components\Menu' => 'menu',
            '\JoshuaTayo\Menu\Components\MobileMenu' => 'MobileMenu',
        ];
    }

    public function registerSettings()
    {
    }
}
