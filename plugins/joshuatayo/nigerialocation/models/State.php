<?php namespace JoshuaTayo\NigeriaLocation\Models;

use Form;
use Model;

/**
 * Model
 */
class State extends Model
{
    use \October\Rain\Database\Traits\Validation;
    

    /**
     * @var string The database table used by the model.
     */
    public $table = 'joshuatayo_nigerialocation_states';

    /**
     * @var array Validation rules
     */
    public $rules = [
        'name' => ['required','unique:joshuatayo_nigerialocation_states'],
        'code' => ['required','unique:joshuatayo_nigerialocation_states'],
    ];

    public $belongsTo = [
        'geopoliticalzone' => ['JoshuaTayo\NigeriaLocation\Models\Geopoliticalzone']
    ];

    public $hasMany = [
        'cities' => [
            'JoshuaTayo\NigeriaLocation\Models\Lga',
            'order' => 'name',
        ],
    ];

    ############################################################################################################
    # GET ATTRIBUTE
    ############################################################################################################

    public function getGeopoliticalzoneCodeAttribute(){
        if (!$this->geopoliticalzone) {
            return;
        }
        return $this->geopoliticalzone->code;
    }

    public function scopeIsEnabled($query)
    {
        return $query->where('is_enabled', true);
    }
    /**
     * @var array Cache for nameList() method
     */
    protected static $nameList = [];

    public static function getNameList($geopoliticalzoneId)
    {
        if (isset(self::$nameList[$geopoliticalzoneId])) {
            return self::$nameList[$geopoliticalzoneId];
        }

        return self::$nameList[$geopoliticalzoneId] = self::whereGeopoliticalzoneId($geopoliticalzoneId)->orderBy('name', 'asc')->lists('name', 'id');
    }

    // public static function getNameList()
    // {
    //     if (self::$nameList) {
    //         return self::$nameList;
    //     }

    //     return self::$nameList = self::isEnabled()->orderBy('is_enabled', 'desc')->orderBy('name', 'asc')->lists('name', 'id');
    // }

    public static function formSelect($name, $selectedValue = null, $options = [])
    {
        return Form::select($name, self::isEnabled()->orderBy('name', 'asc')->lists('name', 'id'), $selectedValue, $options);
    }

    public static function getDefault()
    {
        return ($defaultId = Setting::get('default_state'))
            ? static::find($defaultId)
            : null;
    }
}
