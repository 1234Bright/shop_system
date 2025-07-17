<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['key', 'value', 'type', 'group', 'label'];
    
    /**
     * Get a setting value by key
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function get($key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        
        if (!$setting) {
            return $default;
        }
        
        return self::formatValue($setting->value, $setting->type);
    }
    
    /**
     * Set a setting value
     *
     * @param string $key
     * @param mixed $value
     * @param string $type
     * @param string $group
     * @param string $label
     * @return bool
     */
    public static function set($key, $value, $type = 'text', $group = 'general', $label = null)
    {
        $setting = self::where('key', $key)->first();
        
        if (!$setting) {
            return self::create([
                'key' => $key,
                'value' => $value,
                'type' => $type,
                'group' => $group,
                'label' => $label ?? $key
            ]) ? true : false;
        }
        
        return $setting->update([
            'value' => $value,
            'type' => $type,
            'group' => $group,
            'label' => $label ?? $setting->label
        ]);
    }
    
    /**
     * Get all settings as a key-value array
     *
     * @param string|null $group Filter by group
     * @return array
     */
    public static function getAllSettings($group = null)
    {
        $query = self::query();
        
        if ($group) {
            $query->where('group', $group);
        }
        
        $settings = $query->get();
        $result = [];
        
        foreach ($settings as $setting) {
            $result[$setting->key] = self::formatValue($setting->value, $setting->type);
        }
        
        return $result;
    }
    
    /**
     * Format the value based on type
     *
     * @param mixed $value
     * @param string $type
     * @return mixed
     */
    private static function formatValue($value, $type)
    {
        switch ($type) {
            case 'boolean':
                return (bool) $value;
            case 'integer':
                return (int) $value;
            case 'float':
            case 'double':
                return (float) $value;
            case 'array':
            case 'json':
                return json_decode($value, true) ?? [];
            default:
                return $value;
        }
    }
}
