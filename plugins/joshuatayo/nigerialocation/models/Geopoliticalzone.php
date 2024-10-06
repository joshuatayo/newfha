<?php namespace JoshuaTayo\NigeriaLocation\Models;

use Http;
use Form;
use Model;
use Exception;

/**
 * Model
 */
class Geopoliticalzone extends Model
{
    use \October\Rain\Database\Traits\Validation;
    

    /**
     * @var string The database table used by the model.
     */
    public $table = 'joshuatayo_nigerialocation_geopoliticalzones';

    /**
     * @var array Behaviours implemented by this model.
     */
    public $implement = ['@RainLab.Translate.Behaviors.TranslatableModel'];

    /**
     * @var array The translatable table fields.
     */
    public $translatable = ['name'];

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = ['name', 'code'];

    /**
     * @var array Validation rules
     */
    public $rules = [
        'name' => ['required','unique:joshuatayo_nigerialocation_geopoliticalzones'],
        'code' => ['required','unique:joshuatayo_nigerialocation_geopoliticalzones'],
    ];

    public $hasMany = [
        'states' => [
            'JoshuaTayo\NigeriaLocation\Models\State',
            'order' => 'name',
        ],
    ];


    public function scopeIsEnabled($query)
    {
        return $query->where('is_enabled', true);
    }

    /**
     * @var array Cache for nameList() method
     */
    protected static $nameList = null;

    public static function getNameList()
    {
        if (self::$nameList) {
            return self::$nameList;
        }

        return self::$nameList = self::isEnabled()->orderBy('is_enabled', 'desc')->orderBy('name', 'asc')->lists('name', 'id');
    }

    public static function formSelect($name, $selectedValue = null, $options = [])
    {
        return Form::select($name, self::getNameList(), $selectedValue, $options);
    }

    public static function getDefault()
    {
        return ($defaultId = Setting::get('default_geopoliticalzone'))
            ? static::find($defaultId)
            : null;
    }

    /**
     * Attempts to find a geopoliticalzone from the IP address.
     * @param string $ipAddress
     * @return self
     */
    public static function getFromIp($ipAddress)
    {
        try {
            $body = (string) Http::get('http://ip2c.org/?ip='.$ipAddress);

            if (substr($body, 0, 1) === '1') {
                $code = explode(';', $body)[1];
                return static::where('code', $code)->first();
            }
        }
        catch (Exception $e) {}
    }
}
