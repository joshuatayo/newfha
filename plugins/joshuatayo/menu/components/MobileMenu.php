<?php namespace JoshuaTayo\Menu\Components;

use Cms\Classes\ComponentBase;
use JoshuaTayo\Menu\Models\Menu as MenuModel;

class MobileMenu extends ComponentBase
{
    

	public function componentDetails()
	{
		return [
			'name'        => 'MobileMenu List',
			'description' => 'Display Menu list on mobile nav.'
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