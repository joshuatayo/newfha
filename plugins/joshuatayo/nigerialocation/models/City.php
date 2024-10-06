<?php namespace JoshuaTayo\NigeriaLocation\Models;

use Form;
use Model;

/**
 * Model
 */
class City extends Model
{
    use \October\Rain\Database\Traits\Validation;
    

    /**
     * @var string The database table used by the model.
     */
    public $table = 'joshuatayo_nigerialocation_cities';

    /**
     * @var array Validation rules
     */
    public $rules = [
        'name' => 'required',
    ];

    public $belongsTo = [
        'state' => ['JoshuaTayo\NigeriaLocation\Models\State']
    ];

    public $hasMany = [
        'places' => [
            'JoshuaTayo\NigeriaLocation\Models\Place',
            'order' => 'name',
        ],
    ];

    ############################################################################################################
    # GET ATTRIBUTE
    ############################################################################################################

    public function getStateCodeAttribute(){
        if (!$this->state) {
            return;
        }
        return $this->state->code;
    }

    /**
     * @var array Cache for nameList() method
     */
    protected static $nameList = [];

    public static function getNameList($stateId)
    {
        if (isset(self::$nameList[$stateId])) {
            return self::$nameList[$stateId];
        }

        return self::$nameList[$stateId] = self::whereStateId($stateId)->orderBy('name', 'asc')->lists('name', 'id');
    }

    public static function formSelectState($name, $stateId = null, $selectedValue = null, $options = [])
    {
        return Form::select($name, self::getNameList($stateId), $selectedValue, $options);
    }

    public static function formSelect($name, $selectedValue = null, $options = [])
    {
        return Form::select($name, self::orderBy('name', 'asc')->lists('name', 'id'), $selectedValue, $options);
    }

    public static function getDefault()
    {
        return ($defaultId = Setting::get('default_city'))
            ? static::find($defaultId)
            : null;
    }
}
