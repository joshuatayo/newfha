<?php namespace JoshuaTayo\NigeriaLocation\Models;

use Form;
use Model;

/**
 * Model
 */
class Place extends Model
{
    use \October\Rain\Database\Traits\Validation;
    

    /**
     * @var string The database table used by the model.
     */
    public $table = 'joshuatayo_nigerialocation_places';

    /**
     * @var array Validation rules
     */
    public $rules = [
        'name' => 'required',
    ];

    /**
     * @var array Relations
     */
    public $belongsTo = [
        'city' => ['JoshuaTayo\NigeriaLocation\Models\City']
    ];

    ############################################################################################################
    # GET ATTRIBUTE
    ############################################################################################################

    public function getLgaNameAttribute(){
        if (!$this->city) {
            return;
        }
        return $this->city->name;
    }

    /**
     * @var array Cache for nameList() method
     */
    protected static $nameList = [];

    public static function getNameList($cityId)
    {
        if (isset(self::$nameList[$cityId])) {
            return self::$nameList[$cityId];
        }

        return self::$nameList[$cityId] = self::whereCityId($cityId)->orderBy('name', 'asc')->lists('name', 'id');
    }

    public static function formSelect($name, $cityId = null, $selectedValue = null, $options = [])
    {
        return Form::select($name, self::getNameList($cityId), $selectedValue, $options);
    }

    public static function getDefault()
    {
        return ($defaultId = Setting::get('default_place'))
            ? static::find($defaultId)
            : null;
    }
}
