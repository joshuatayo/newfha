<?php namespace JoshuaTayo\Property\Components;

use Str;
use Request;
use Cms\Classes\Page;
use Cms\Classes\ComponentBase;
use Input;
use JoshuaTayo\NigeriaLocation\Models\Setting;
use JoshuaTayo\Property\Models\Property as PropertyModel;

class MapPropertyList extends ComponentBase
{
	public  $markers,
            $data,
            $detailPage;

    public function componentDetails() {
        return [
          'name'        => 'MapPropertyList',
          'description' => 'Display Properties on Map'
        ];
    }

    public function defineProperties() {
    	$settings             = Setting::instance();
        return [
            'mapTitle' => [
                'title'       => 'Map Title',
                'description' => 'The tile of the map',
                'type'        => 'string',
                'default'     => '',
            ],
            'mapStyle' => [
                'title'       => 'Map Style',
                'type'        => 'dropdown',
                'default'     => 'default',
                'options'           => $this->getStyleOptions()
            ],
            'centerLat' => [
                'title'       => 'Center latitude',
                'description' => 'Enter Center latitude',
                'type'        => 'string',
                'default'     => 0.0,
            ],
            'centerLng' => [
                'title'       => 'Center longitude',
                'description' => 'Enter Center longitude',
                'type'        => 'string',
                'default'     => 0.0,
            ],
            'zoom' => [
                'title'       => 'Default zoom',
                'description' => 'Enter zoom',
                'type'        => 'string',
                'default'     => 2,
            ],
            'mapMarker' => [
                'title'       => 'Icon path',
                'description' => 'Marker Icon path',
                'default'     => '',
                'type'        => 'string',
                'group'       => 'Marker icon',
            ],
            'iconAnimation' => [
                'title'       => 'Icon Animation',
                'description' => 'Marker Icon animation name',
                'default'     => '',
                'type'        => 'string',
                'group'       => 'Marker icon',
            ],
            'iconXOffset' => [
                'title'             => 'X offset',
                'description'       => 'X offset',
                'default'           => 0,
                'type'              => 'string',
                'validationMessage' => 'Must be a String',
                'validationPattern' => '^[0-9]+$',
                'group'             => 'Marker icon',
            ],
            'iconYOffset' => [
                'title'             => 'Y offset',
                'description'       => 'Y offset',
                'default'           => 0,
                'type'              => 'string',
                'validationMessage' => 'Must be a String',
                'validationPattern' => '^[0-9]+$',
                'group'             => 'Marker icon',
            ],
            'detailPage' => [
                'title'             => 'Post page',
                'type'              => 'dropdown',
                'group'             => 'Links',
                'options'           => $this->getPageOptions()
            ],
            'thumbMode' => [
                'title'       => 'Thumb Mode',
                'description' => 'mode of thumb generation',
                'type'        => 'dropdown',
                'default'     => 'auto',
                'group'             => 'Thumb',
            ],
            'thumbWidth' => [
                'title'             => 'Thumb Width',
                'description'       => 'Width of the thumb to be generated',
                'default'           => 360,
                'type'              => 'string',
                'validationMessage' => 'Thumb width must be a number',
                'validationPattern' => '^[0-9]+$',
                'required'          => FALSE,
                'group'             => 'Thumb',
            ],
            'thumbHeight' => [
                'title'             => 'Thumb Height',
                'description'       => 'Height of the thumb to be generated',
                'default'           => 230,
                'type'              => 'string',
                'validationMessage' => 'Thumb height must be a number',
                'validationPattern' => '^[0-9]+$',
                'required'          => FALSE,
                'group'             => 'Thumb',
            ],
        ];
    }

    public function getStyleOptions()
    {
        return [
            ''        => 'default',
            'skin1'   => 'Style 1',
            'skin2'   => 'Style 2',
            'skin3'   => 'Style 3',
            'skin4'   => 'Style 4',
        ];
    }

    public function getPageOptions()
    {
        return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }

    public function getThumbModeOptions() {
    	return [
        	'auto' => 'Auto',
        	'exact' => 'Exact',
        	'portrait' => 'Portrait',
        	'landscape' => 'Landscape',
        	'crop' => 'Crop',
      	];
  	}

  	protected function addMapAssets() {
        //add google map js with or without api key
        $key = (Setting::get('google_maps_key')) ? 'key=' . Setting::get('google_maps_key') . '&' : '';
        $this->addJs(
          'https://maps.googleapis.com/maps/api/js?' . $key . 'callback=mapComponentInit'
        );
        $this->addJs('/plugins/joshuatayo/property/assets/js/google-maps.js');
    }

    public function onRun() {
      	$this->prepareVars();
        $settings       =   Setting::instance();
        $this->data     =   $this->page['data']       = $this->onDataLoad();
        $this->addMapAssets();
    }

    public function prepareVars()
    {
        $settings           =   Setting::instance();
        $this->mapTitle     =   $this->page['mapTitle'] = $this->property('mapTitle');
        $this->mapStyle     =   $this->page['mapStyle'] = $this->property('mapStyle');
        $this->centerLat    =   $this->page['centerLat'] = $this->property('centerLat');
        $this->centerLng    =   $this->page['centerLng'] = $this->property('centerLng');
        $this->zoom         =   $this->page['zoom'] = $this->property('zoom');
        $this->mapMarker    =   $this->page['mapMarker'] = $this->property('mapMarker');
    }

    public function onDataLoad() {
        $data               =   array();
        $data['settings']   =   $this->createSettingsArray();
        $markers            =   $this->loadMarkers();
        $data['markers']    =   $markers->toArray();
        return json_encode($data);
    }

    public function loadMarkers() {

        $param          =[
            'title'         => Input::get('title', null),
            'type'          => Input::get('type', $this->typeId),
            'status'        => Input::get('status', $this->stateId),
            'bedroom'       => Input::get('bedroom', $this->bedroomId),
            'bathdroom'     => Input::get('bathroom', $this->bathroomId),
            'state'         => Input::get('state', $this->stateId),
            'city'          => Input::get('city', $this->cityId),
            'place'         => Input::get('place', $this->placeId),
            'amenities'     => Input::get('amenities', null),
            'price'         => Input::get('price', null)
        ];
        $markers          = PropertyModel::ListFrontEndMap($param)->get();

      return $markers;
    }

    protected function createSettingsArray() {
        $settings = array();
        $settings['animation'] = $this->property('iconAnimation');
        $settings['x_offset'] = $this->property('iconXOffset');
        $settings['y_offset'] = $this->property('iconYOffset');
        return $settings;
    }

    public function onMarkerClicked() {
      	$id      =   Request::input('marker_id');
      	$query   =   PropertyModel::where('id', $id);

      	$model   =   $query->with('property_images')->first();

      	$model->thumb = $model->getMarkerThumb([
        	'width'    =>  $this->property('thumbWidth'),
        	'height'   =>  $this->property('thumbHeight'),
        	'mode'     =>  $this->property('thumbMode'),
      	]);

      	$model->url = $this->getSingleUrl($model);
      	return $this->renderPartial('::popup', ['marker' => $model]);
  	}

  	protected function getSingleUrl(PropertyModel $markers) {
	    $url = '';
	    $this->detailPage     =    $this->page['detailPage']   = $this->property('detailPage');
	    $url   =    $markers->setUrl($this->detailPage, $this->controller);
	    return $url;
	}
}
