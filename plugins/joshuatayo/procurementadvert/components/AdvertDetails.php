<?php namespace JoshuaTayo\ProcurementAdvert\Components;

use Cms\Classes\ComponentBase;
use Cms\Classes\Page;
use JoshuaTayo\ProcurementAdvert\Models\Advert as AdvertModel;

class AdvertDetails extends ComponentBase
{
    public $advert,
            $categoryPage;

    public function componentDetails()
    {
        return [
            'name'        => 'Advert Details',
            'description' => 'Display advert details'
        ];
    }

    public function defineProperties()
    {
        return [
            'slug' => [
                'title'       => 'Slug',
                'type'        => 'string',
                'default'     => '{{ :slug }}'
            ],
            'categoryPage' => [
                'title'             => 'Category page',
                'type'              => 'dropdown',
                'group'             => 'Links',
                'options'           => $this->getPageOptions()
            ],
        ];
    }

    public function getPageOptions()
    {
        return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }

    public function onRun()
    {
        $this->categoryPage         = $this->page['categoryPage'] = $this->property('categoryPage');
        $this->advert = $this->page['advert'] = $this->loadDetails();
    }

    protected function loadDetails()
    {
        $slug = $this->property('slug');
        $advert = AdvertModel::where('slug', $slug)->first();

        if(!empty($advert->category))
        {
            $advert->category->setUrl($this->categoryPage, $this->controller);
        }

        return $advert;
    }
}
