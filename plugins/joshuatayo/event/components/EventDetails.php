<?php namespace JoshuaTayo\Event\Components;

use Cms\Classes\ComponentBase;
use Cms\Classes\Page;
use JoshuaTayo\Event\Models\Event as EventModel;

class EventDetails extends ComponentBase
{
    public $event,
            $categoryPage;

    public function componentDetails()
    {
        return [
            'name'        => 'Event Details',
            'description' => 'Display Event Details'
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
        $this->event = $this->page['event'] = $this->loadDetails();
    }

    protected function loadDetails()
    {
        $slug = $this->property('slug');
        $event = EventModel::where('slug', $slug)->first();

        if(!empty($event->category))
        {
            $event->category->setUrl($this->categoryPage, $this->controller);
        }

        return $event;
    }
}
