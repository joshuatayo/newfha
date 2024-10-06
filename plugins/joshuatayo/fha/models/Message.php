<?php namespace JoshuaTayo\Fha\Models;

use Model;

/**
 * Model
 */
class Message extends Model
{
    use \October\Rain\Database\Traits\Validation;
    

    /**
     * @var string The database table used by the model.
     */
    public $table = 'joshuatayo_fha_messages';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    /**
     * Scope new messages only
     */
    public function scopeIsNew($query)
    {
        return $query->where('new_message', true);
    }

    /**
     * Scope read messages only
     */
    public function scopeIsRead($query)
    {
        return $query->where('new_message', false);
    }

    /**
     * Scope read messages only
     */
    public function scopeIsReplied($query)
    {
        return $query->where('is_replied', true);
    }
}
