<?php namespace JoshuaTayo\Property\Components;

use Str;
use Lang;
use Redirect;
use BackendAuth;
use Cms\Classes\Page;
use Cms\Classes\ComponentBase;
use JoshuaTayo\Property\Models\Property as PropertyModel;

class PropertyDetails extends ComponentBase
{
	public $data;

	public function componentDetails()
    {
        return [
            'name'        => 'PropertyDetails',
            'description' => 'Display Property Details on page'
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
            'searchPage' => [
                'title'             => 'Search page',
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
        $data = $this->loadItem();
        $this->data = $this->page['data'] = $data;
        $this->updateItemViewCount();
    }

    protected function loadItem()
    {

        $slug = $this->property('slug');
        $data = PropertyModel::isEnabled()->where('slug', $slug)->first();

        return $data;
    }

    protected function updateItemViewCount()
    {
        if (empty($this->data->id)) { return; }
        //save vaidate error
       $this->data->update([
           'views' => intval($this->data->view)
       ]);
        $this->data->increment('views');
    }
}
