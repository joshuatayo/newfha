<?php namespace JoshuaTayo\Fha\Models;

use Model;
use Cms\Classes\Theme;
use Cms\Classes\Page;
class Settings extends Model
{
        
    public $implement = ['System.Behaviors.SettingsModel'];

    // A unique code
    public $settingsCode = 'joshuatayo_fha_settings';

    // Reference to field configuration
    public $settingsFields = 'fields.yaml';

    public function getRedirectToUrlOptions($keyValue = null){

        $currentTheme = Theme::getEditTheme();
        $allPages = Page::listInTheme($currentTheme, true);

        $pages = [];
        $pages[""] = "--Select--";

        foreach ($allPages as $pg) {
            $pages[$pg->url] = $pg->title;
        }

        return $pages;
    }
}