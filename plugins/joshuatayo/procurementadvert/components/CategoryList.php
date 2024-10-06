<?php namespace JoshuaTayo\ProcurementAdvert\Components;

use Cms\Classes\ComponentBase;
use Cms\Classes\Page;
use JoshuaTayo\ProcurementAdvert\Models\Category as CategoryModel;


class CategoryList extends ComponentBase
{
    public  $categories,
            $categoryPage;

    public function componentDetails()
    {
        return [
            'name'        => 'Category List',
            'description' => 'Display Advert Categories'
        ];
    }

    public function defineProperties()
    {
        return [
            'categoryPage' => [
                'title'             => 'Category page',
                'type'              => 'dropdown',
                'default'           => '',
            ],
        ];
    }

    public function getCategoryPageOptions()
    {
        return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }

    public function onRun()
    {
        $this->categoryPage     = $this->page['categoryPage']   = $this->property('categoryPage');
        $this->categories = $this->page['categories'] = $this->loadList(
            CategoryModel::isEnabled()
                ->orderBy('name', 'asc')
                ->get()
                ->filter(function ($category) {
                    return $category->getAdvertsCountAttribute() > 0;
                })
        );
    }

    protected function loadList($categories)
    {
        return $categories->each(function($category) {
            $category->setUrl($this->categoryPage, $this->controller);
        });
    }
}
