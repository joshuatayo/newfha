<?php namespace JoshuaTayo\NigeriaLocation;

use Backend;
use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public function registerComponents()
    {
    }

    public function registerSettings()
    {
    	return [
            'geopoliticalzones' => [
                'label'       => 'Geopolitical Zone',
                'description' => 'Manage available user Geopolitical Zones.',
                'category'    => 'Location in Nigeria',
                'icon'        => 'icon-globe',
                'url'         => Backend::url('joshuatayo/nigerialocation/geopoliticalzones'),
                'order'       => 500,
                'keywords'    => 'geopoliticalzone, geopoliticalzones',
            ],
            'states' => [
                'label'       => 'State',
                'description' => 'Manage available user States.',
                'category'    => 'Location in Nigeria',
                'icon'        => 'icon-globe',
                'url'         => Backend::url('joshuatayo/nigerialocation/states'),
                'order'       => 500,
                'keywords'    => 'state, states',
            ],
            'cities' => [
                'label'       => 'City',
                'description' => 'Manage available user Cities and Places.',
                'category'    => 'Location in Nigeria',
                'icon'        => 'icon-globe',
                'url'         => Backend::url('joshuatayo/nigerialocation/cities'),
                'order'       => 500,
                'keywords'    => 'city, cities, place, places',
            ],
            'places' => [
                'label'       => 'Place',
                'description' => 'Manage available user continents and countries.',
                'category'    => 'Location in Nigeria',
                'icon'        => 'icon-globe',
                'url'         => Backend::url('joshuatayo/nigerialocation/places'),
                'order'       => 500,
                'keywords'    => 'place, places',
            ],
            'settings' => [
                'label'       => 'Location settings',
                'description' => 'Manage Nigeria location based settings.',
                'category'    => 'Location in Nigeria',
                'icon'        => 'icon-map-signs',
                'class'       => 'JoshuaTayo\NigeriaLocation\Models\Setting',
                'order'       => 600,
            ]
        ];
    }
}
