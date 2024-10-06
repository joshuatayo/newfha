<?php namespace JoshuaTayo\Fha\Components;

use Str;
use Cms\Classes\Page;
use Cms\Classes\ComponentBase;
use JoshuaTayo\Fha\Models\Project as ProjectModel;

class ProjectDetails extends ComponentBase
{ 
	public $project;

	public function componentDetails()
    {
        return [
            'name'        => 'ProjectDetails',
            'description' => 'Display Project Details on page'
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
        ];
    }

    public function getPageOptions()
    {
        return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }

    public function onRun()
    {
        $this->project = $this->page['project'] = $this->loadItem();
        
    }

    protected function loadItem()
    {

        $slug = $this->property('slug');
        $project = ProjectModel::isEnabled()->where('slug', $slug)->first();

        return $project;
    }
}