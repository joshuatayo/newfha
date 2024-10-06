<?php namespace JoshuaTayo\AboutInfo\Components;

use Cms\Classes\ComponentBase;
use JoshuaTayo\AboutInfo\Models\ManagementTeam as ManagementTeamModel;
use Cms\Classes\Page;

class ManagementTeamList extends ComponentBase
{
    public $managementTeams,
            $noManagementTeamMessage,
            $detailPage,
            $pageParam,
            $managementTeamsPerPage;

    public function componentDetails()
    {
        return [
            'name'        => 'Management Team List',
            'description' => 'Display management team list on page',
        ];
    }


    public function defineProperties()
    {
        return [
            'noManagementTeamMessage' => [
                'title'             => 'No Management Team message',
                'description'       => 'Message to display if no management team is found',
                'type'              => 'string',
                'default'           => 'No Management Team found',
                'showExternalParam' => false
            ],
            'detailPage' => [
                'title'             => 'Management Team page',
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
        $this->prepareVars();
    }

    public function prepareVars()
    {
        $this->noManagementTeamMessage  =   $this->page['noManagementTeamMessage']   =   $this->property('noManagementTeamMessage');
        $this->detailPage   =   $this->page['detailPage']     =   $this->property('detailPage');
        $this->sectionOptions   =    $this->page['sectionOptions']     =    ManagementTeamModel::getSectionOptions();
        $this->managementTeams  =   $this->page['managementTeams'] = ManagementTeamModel::get('team_members');
    }
}

