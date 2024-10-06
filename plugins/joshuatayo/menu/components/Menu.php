<?php namespace JoshuaTayo\Menu\Components;

use Cms\Classes\ComponentBase;
use JoshuaTayo\Menu\Models\Menu as MenuModel;

class Menu extends ComponentBase
{
    

	public function componentDetails()
	{
		return [
			'name'        => 'Menu List',
			'description' => 'Display Menu list on nav'
		];
	}

	

	public function onRun()
    {
        $this->menus = $this->page['menus'] = $this->loadMenu();
    }

	public function loadMenu()
	{
		return MenuModel::isEnabled()->orderBy('sorting', 'asc')->get();
	}
}