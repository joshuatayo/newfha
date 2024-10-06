<?php namespace JoshuaTayo\News\Components;

use Cms\Classes\ComponentBase;
use Cms\Classes\Page;
use JoshuaTayo\News\Models\Category as CategoryModel;


class Categories extends ComponentBase
{
    public  $categories,
            $categoryPage,
            $noPostsMessage;           

    public function componentDetails()
    {
        return [
            'name'        => 'Categories',
            'description' => 'Display News Categories'
        ];
    }

    public function defineProperties()
    {
        return [
            // 'slug' => [
            //     'title'       => 'Category slug',
            //     'default'     => '{{ :slug }}',
            //     'type'        => 'string',
            // ],
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
        $this->categories = $this->page['categories'] = $this->listCategories(CategoryModel::get());
    }

    protected function listCategories($categories)
    {

        return $categories->each(function($category) {
            $category->setUrl($this->categoryPage, $this->controller);
        });

    }
}
