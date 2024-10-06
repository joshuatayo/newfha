<?php namespace JoshuaTayo\Property;

use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public function registerComponents()
    {
        return [
            '\JoshuaTayo\Property\Components\FilterForm' => 'filterform',
            '\JoshuaTayo\Property\Components\MapPropertyList'    => 'mappropertylist',
            '\JoshuaTayo\Property\Components\PropertyList'    => 'propertylist',
            '\JoshuaTayo\Property\Components\PropertyDetails'    => 'propertydetails',
            '\JoshuaTayo\Property\Components\FeatureProperty'    => 'featureproperty',
        ];
    }

    public function registerSettings()
    {
    }

    /**
     * Register new Twig variables
     * @return array
     */
    public function registerMarkupTags()
    {
        return [
            'functions' => [
                'form_select_city' => ['JoshuaTayo\NigeriaLocation\Models\City', 'formSelect'],
                
                'form_select_place'  => ['JoshuaTayo\NigeriaLocation\Models\Place', 'formSelect']
            ], 
        ];
    }
}
