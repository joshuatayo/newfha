<?php namespace JoshuaTayo\Fha\Components;

use Str;
use Cms\Classes\Page;
use Cms\Classes\ComponentBase;
use JoshuaTayo\Fha\Models\Gallery as GalleryModel;

class GalleryImages extends ComponentBase
{ 
	public $photo;

	public function componentDetails()
    {
        return [
            'name'        => 'GalleryImages',
            'description' => 'Display Gallery Images on page'
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
            'detailPage' => [
                'title'             => 'Post page',
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
        $this->photo = $this->page['photo'] = $this->loadItem();
    }

    protected function loadItem()
    {

        $slug = $this->property('slug');
        $photo = GalleryModel::isEnabled()->where('slug', $slug)->first();

        return $photo;
    }
}