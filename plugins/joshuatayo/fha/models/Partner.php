<?php namespace JoshuaTayo\Fha\Models;

use Model;
use Carbon\Carbon;

/**
 * Model
 */
class Partner extends Model
{
    use \October\Rain\Database\Traits\Validation;
    

    /**
     * @var string The database table used by the model.
     */
    public $table = 'joshuatayo_fha_partners';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    /**
     * @var array Relation
     */
    public $attachOne = [
        'partner_logo' => ['System\Models\File'],
    ];

    /**
     * @var array Scope
     */
    public function scopeIsEnabled($query)
    {
        return $query
            ->where('is_enabled', true)
            ->where('created_at', '<', Carbon::now());
    }
}
