<?php namespace JoshuaTayo\NigeriaLocation\Models;

use Model;

class Setting extends Model
{
    public $implement = ['System.Behaviors.SettingsModel'];

    public $settingsCode = 'nigerialocation_settings';
    public $settingsFields = 'fields.yaml';

    public function initSettingsData()
    {
        $this->google_maps_key = '';
        $this->default_geopoliticalzone = '';
        $this->default_state = '';
        $this->default_city = '';
        $this->default_place = '';
    }

    public function getDefaultGeopoliticalzoneOptions()
    {
        return Geopoliticalzone::getNameList();
    }
    public function getDefaultStateOptions()
    {
        return State::getNameList($this->default_geopoliticalzone);
    }
    public function getDefaultCityOptions()
    {
        return City::getNameList($this->default_state);
    }
    public function getDefaultPlaceOptions()
    {
        return Place::getNameList($this->default_city);
    }
}
