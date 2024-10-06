<?php namespace JoshuaTayo\AboutInfo;

use System\Classes\PluginBase;

/**
 * Plugin class
 */
class Plugin extends PluginBase
{
    /**
     * register method, called when the plugin is first registered.
     */
    public function register()
    {
    }

    /**
     * boot method, called right before the request route.
     */
    public function boot()
    {
    }

    /**
     * registerComponents used by the frontend.
     */
    public function registerComponents()
    {
        return [
            'JoshuaTayo\AboutInfo\Components\ManagementTeamList' => 'managementTeamList',
            'JoshuaTayo\AboutInfo\Components\ManagementTeamDetails' => 'managementTeamDetails',
        ];
    }

    /**
     * registerSettings used by the backend.
     */
    public function registerSettings()
    {
        return [
            'managementTeams' => [
                'label'       => 'Management Teams',
                'icon'        => 'icon-users',
                'description' => 'Manage Management Teams.',
                'class'       => 'JoshuaTayo\AboutInfo\Models\ManagementTeam',
                'order'       => 60,
                'permissions' => ['joshuatayo.aboutinfo.manage_aboutinfo.access_management_teams'],
                'category' => 'About FMACCE'
            ]
        ];
    }
}
