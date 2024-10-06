<?php namespace JoshuaTayo\AboutInfo\Models;

use Model;
use ValidationException;
use Str;

class ManagementTeam extends Model
{
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var array implement behaviors
     */
    public $implement = [\System\Behaviors\SettingsModel::class];

    /**
     * @var string settingsCode is a unique code for this object.
     */
    public $settingsCode = 'joshuatayo_aboutinfo_management_teams';

    public $settingsFields = 'fields.yaml';

    /**
     * @var array rules for validation
     */
    public $rules = [
    ];

    public static function getSectionOptions()
    {
        return [
            'section-01' => 'Section 01',
            'section-02' => 'Section 02',
            'section-03' => 'Section 03',
        ];
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
     * Validate the slug format.
     *
     * @param string $slug
     * @return bool
     */
    protected function isValidSlug($slug)
    {
        // Slug must match the pattern: only lowercase letters, numbers, and hyphens
        // Must not start or end with a hyphen, and must not contain spaces
        return preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $slug);
    }

    protected function generateSlug($name)
    {
        // Replace spaces with dashes, lowercase, and remove special characters
        return strtolower(preg_replace('/[^A-Za-z0-9-]+/', '-', $name));
    }

    /**
     * Before validate method to enforce custom validation rules.
     */
    public function beforeValidate()
    {
        // Define the maximum file size in bytes (2MB in this example)
        $maxFileSize = 2 * 1024 * 1024; // 2MB

        // Validate each repeater item
        if (isset($this->team_members) && is_array($this->team_members)) {
            foreach ($this->team_members as $index => $teamMember) {
                // Validate photo
                if (empty($teamMember['photo'])) {
                    throw new ValidationException(['team_members.' . $index . '.photo' => 'The Photo field is required']);
                }

                if (empty($teamMember['full_name'])) {
                    throw new ValidationException(['team_members.' . $index . '.full_name' => 'The Full Name field is required.']);
                }

                if (empty($teamMember['slug']) && !$this->isValidSlug($teamMember['slug'])) {
                    throw new ValidationException(['team_members.' . $index . '.slug' => 'The Slug field is required and must be in the correct format (lowercase letters, numbers, and hyphens only, no spaces, cannot start or end with a hyphen).']);
                }

                if ($teamMember['slug'] != Str::slug($teamMember['full_name'])) {
                    throw new ValidationException(['team_members.' . $index . '.slug' => 'The Slug field must be the same as full name field just like "'. Str::slug($teamMember['full_name']).'"']);
                }

                if (empty($teamMember['position'])) {
                    throw new ValidationException(['team_members.' . $index . '.position' => 'The position field is required.']);
                }
            }
        }
    }
}
