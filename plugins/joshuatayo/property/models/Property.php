<?php namespace JoshuaTayo\Property\Models;

use Str;
use Model;
use Carbon\Carbon;
use JoshuaTayo\NigeriaLocation\Models\Geopoliticalzone;
use JoshuaTayo\NigeriaLocation\Models\State;
use JoshuaTayo\NigeriaLocation\Models\City;
use JoshuaTayo\NigeriaLocation\Models\Place;

/**
 * Model
 */
class Property extends Model
{
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'joshuatayo_property_properties';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    protected $fillable = ['view'];

    /**
     * @var array Sorting Options
     */
    public static $allowedSortingOptions = array(
        'title'         => 'title',
        'price'         => 'Price',
        'created_at'    => 'Created',
        'updated_at'    => 'Updated',
        'view'         => 'View'
    );

    /**
     * @var array Relations
     */
    public $belongsTo = [
        'type' => ['JoshuaTayo\Property\Models\Type'],
        'status' => ['JoshuaTayo\Property\Models\Status'],
        'bedroom' => ['JoshuaTayo\Property\Models\Bedroom'],
        'bathroom' => ['JoshuaTayo\Property\Models\Bathroom'],
        'state' => ['JoshuaTayo\NigeriaLocation\Models\State'],
        'city' => ['JoshuaTayo\NigeriaLocation\Models\City'],
        'place' => ['JoshuaTayo\NigeriaLocation\Models\Place'],
    ];

    public $attachMany = [
        'property_images' => ['System\Models\File'],
    ];
    
    public $belongsToMany = [
        'amenities' => [
            'JoshuaTayo\Property\Models\Amenity',
            'table' => 'joshuatayo_property_properties_amenities',
            'order' => 'name'
        ],
    ];
    
    public $hasMany = [
        'features' => [
            'JoshuaTayo\Property\Models\Feature',
            'order' => 'sorting',
        ],
        'floorplans' => [
            'JoshuaTayo\Property\Models\Floorplan',
            'order' => 'name',
        ],
    ];

    /**
     * @var array Get Attribute
     */
    public function getTypeNameAttribute(){
        if (!$this->type) {
            return;
        }
        return $this->type->name;
    }
    
    public function getStatusNameAttribute(){
        if (!$this->status) {
            return;
        }
        return $this->status->name;
    }

    public function getBedroomNameAttribute(){
        if (!$this->bedroom) {
            return;
        }
        return $this->bedroom->value;
    }

    public function getBathroomNameAttribute(){
        if (!$this->bathroom) {
            return;
        }
        return $this->bathroom->value;
    }

    // public function getStateNameAttribute(){
    //     if (!$this->state) {
    //         return;
    //     }
    //     return $this->state->name;
    // }

    // public function getCityNameAttribute(){
    //     if (!$this->city) {
    //         return;
    //     }
    //     return $this->city->name;
    // }

    // public function getPlaceNameAttribute(){
    //     if (!$this->place) {
    //         return;
    //     }
    //     return $this->place->name;
    // }
    public function filterFields($fields, $context = null)
    {

        // do something to get the name value based on the code
        $pref = Str::random(5);
        $number = str_finish("NGN", $pref);
        $fields->property_ref->value = $number;

    }

    // public function setPropertyRefOptions($p_ref)
    // {
    //     $p_ref = Str::random(4)
    //     return $this->$p_ref;
    // } 

    public function getGeopoliticalzoneIdOptions()
    {
        return Geopoliticalzone::getNameList();
    }

    public function getStateIdOptions()
    {
        return State::getNameList($this->geopoliticalzone_id);
    }

    public function getCityIdOptions()
    {
        return City::getNameList($this->state_id);
    }

    public function getPlaceIdOptions()
    {
        return Place::getNameList($this->city_id);
    }

    public function getFormatPriceAttribute(){
        return @number_format($this->price, 2, '.', ',');
    }

    // public function getPropertyimagesAttribute()
    // {
    //     foreach ($this->property_images as $image) {
    //         return '<img src="'.$image->getThumb(110, 30, ['mode' => 'crop']).'" alt="" />';
    //     }
    // }

    public function getSrcimageAttribute($value, $w = 959, $h = 600)
    {
        foreach ($this->property_images as $image) {
            return $image->getThumb($w, $h, ['mode' => 'crop']);
        }
    }

    /**
     * @var array Set Attribute
     */
    public function setUrl($pageName, $controller)
    {
        $params = [
            'id' => $this->id,
            'slug' => $this->slug,
        ];

        return $this->url = $controller->pageUrl($pageName, $params);
    }

    /**
     * @var array Scope
     */

    public function scopeIsEnabled($query)
    {
        return $query
            ->where('is_enabled', true)
            ->where('created_at', '<', Carbon::now());
    }

    public function scopeIsFeatured($query)
    {
        return $query
            ->where('is_feature', true)
            ->where('created_at', '<', Carbon::now());
    }

    public function scopeListFrontEnd($query, $options)
    {
        /*
         * Default options
         */
        extract(array_merge([
            'page'          => 1,
            'perPage'       => 30,
            'sort'          => 'created_at',
            'order'         => 'desc',
            'title'          => null,
            'type'          => null,
            'status'        => null,
            'bedroom'       => null,
            'bathroom'      => null,
            'state'         => null,
            'city'          => null,
            'place'         => null,
            'price'         => null,
            'amenities'     => null
        ], $options));


        $query->isEnabled();

        if (!empty($title)) {
            $query->where('title', 'like', '%'.$title.'%');
        }

        if(!empty($type)){
            $query->where("type_id", "=", $type);
        }

        if(!empty($status)){
            $query->where("status_id", "=", $status);
        }

        if(!empty($bedroom)){
            $query->where("bedroom_id", "=", $bedroom);
        }

        if(!empty($bathroom)){
            $query->where("bathroom_id", "=", $bathroom);
        }

        if(!empty($state)){
            $query->where("state_id", "=", $state);
        }

        if(!empty($city)){
            $query->where("city_id", "=", $city);
        }

        if(!empty($place)){
            $query->where("place_id", "=", $place);
        }

        if(!empty($price)){
            if(is_array($price) && count($price) == 2){
                $query->whereBetween('price', [min($price), max($price)]);
            }else{
                $query->where("price", ">=", floatval($price));
            }
        }

        // if (!empty($amenities)) {
        //     $query->where('amenities.name', 'like', '%'.$amenities.'%');
        // }

        // if(!empty($amenities)){
        //     if(!is_array($amenities)){
        //         if(strpos($amenities, ",") !== false)
        //         {
        //             $amenities   = array_map("trim", explode(',', $amenities));
        //         }
        //         else
        //         {
        //             $amenities = [$amenities];
        //         }
        //     }
        //     $query->whereHas('amenities', function($q) use ($amenities) {
        //         $q->search($amenities);
        //     });
        // }

        if(!in_array($sort, self::$allowedSortingOptions)){
            $sort = 'created_at';
        }

        if($order != 'desc'){
            $order = 'asc';
        }

        $query->orderBy($sort, $order);


        return $query->paginate($perPage, $page);
    }

    public function scopeListFrontEndMap($query, $options)
    {
        extract(array_merge([
            'title'         => null,
            'type'          => null,
            'status'        => null,
            'bedroom'       => null,
            'bathroom'      => null,
            'state'         => null,
            'city'          => null,
            'place'         => null,
            'price'         => null,
            'amenities'     => null
        ], $options));

        $query->isEnabled();

        if (!empty($title)) {
            $query->where('title', 'like', '%'.$title.'%');
        }

        if(!empty($type)){
            $query->where("type_id", "=", $type);
        }

        if(!empty($status)){
            $query->where("status_id", "=", $status);
        }

        if(!empty($bedroom)){
            $query->where("bedroom_id", "=", $bedroom);
        }

        if(!empty($bathroom)){
            $query->where("bathroom_id", "=", $bathroom);
        }

        if(!empty($state)){
            $query->where("state_id", "=", $state);
        }

        if(!empty($city)){
            $query->where("city_id", "=", $city);
        }

        if(!empty($place)){
            $query->where("place_id", "=", $place);
        }

        if(!empty($price)){
            if(is_array($price) && count($price) == 2){
                $query->whereBetween('price', [min($price), max($price)]);
            }else{
                $query->where("price", ">=", floatval($price));
            }
        }

        return $query;
    }

    public function getMarkerThumb($options) {

        if ($this->property_images) {
            foreach ($this->property_images as $image) {
                return $image->getThumb(
                  $options['width'],
                  $options['height'],
                  ['mode' => $options['mode']]
                );
            }
        }
      return '';
    }
}
