<?php namespace JoshuaTayo\Property\Components;

use Str;
use Lang;
use Redirect;
use Cms\Classes\Page;
use Cms\Classes\ComponentBase;
use JoshuaTayo\Property\Models\Property;
use JoshuaTayo\Property\Models\Amenity;
use JoshuaTayo\Property\Models\Type;
use JoshuaTayo\Property\Models\Status;
use JoshuaTayo\Property\Models\Bedroom;
use JoshuaTayo\Property\Models\Bathroom;
use JoshuaTayo\NigeriaLocation\Models\City;
use JoshuaTayo\NigeriaLocation\Models\Place;

class FilterForm extends ComponentBase
{
    public  $filterPage,
            $displayType,
            $amenityList,

            $types = null,
            $status = null,
            $bedrooms = null,
            $bathrooms = null,
            $cities = null,
            $places = null,
            $amenities = null;

    public function componentDetails()
    {
        return [
            'name'        => 'Filter Form',
            'description' => 'Display filter form on web page'
        ];
    }

    public function defineProperties()
    {
        return [
            'displayType' => [
                'title'             => 'Display Type',
                'type'              => 'dropdown',
                'default'           => '',
                'options'           => $this->getDisplayTypeOptions()
            ],
            'filterPage' => [
                'title'             => 'Filter page',
                'type'              => 'dropdown',
                'options'           => $this->getPageOptions(),
                'group'             => 'Links'
            ],
            'amenityList' => [
                'title'             => 'Amenity List',
                'description'       => false,
                'type'              => 'checkbox'
            ],
        ];
    }

    public function getPageOptions()
    {
        return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }

    public function getDisplayTypeOptions()
    {
        return [
            ''                   => 'default',
            'largeFilter'        => 'Large Filter',
            'smallFilter'        => 'Small Filter',
        ];
    }

    public function init()
    {
        $this->page['displayType']   =
        $this->displayType           = $this->property('displayType');

        $this->page['filterPage']   =
        $this->filterPage           = $this->property('filterPage');

        $this->page['amenityList']  = 
        $this->amenityList          = $this->property('amenityList');

        $this->page['cities']       =
        $this->cities               = City::orderBy("name","asc")->get();

        $this->page['places']       =
        $this->places               = Place::orderBy("name","asc")->get();

        $this->page['types']        =
        $this->types                = Type::orderBy("name","asc")->get();

        $this->page['status']       =
        $this->status               = Status::orderBy("name","asc")->get();

        $this->page['bedrooms']     =
        $this->bedrooms             = Bedroom::orderBy("name","asc")->get();

        $this->page['bathrooms']    =
        $this->bathrooms            = Bathroom::orderBy("name","asc")->get();

        $this->page['amenities']    =
        $this->amenities            = Amenity::orderBy("name","asc")->get();
    }
}