<?php namespace JoshuaTayo\AboutInfo\Components;

use Cms\Classes\ComponentBase;
use JoshuaTayo\AboutInfo\Models\ManagementTeam as ManagementTeamModel;

class ManagementTeamDetails extends ComponentBase
{
    public $managementTeam;

    public function componentDetails()
    {
        return [
            'name'        => 'Management Team Details',
            'description' => 'Display management team details on page',
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

    public function onRun()
    {
        $this->managementTeam     =    $this->page['managementTeam']     =    $this->loadPost();
    }

    protected function loadPost()
    {
        $slug = $this->property('slug');
        $managementTeams = ManagementTeamModel::instance()->team_members;

        foreach ($managementTeams as $teamMember) {
            if (isset($teamMember['slug']) && $teamMember['slug'] === $slug) {
                return $teamMember;
            }
        }

        return null;
    }
}

