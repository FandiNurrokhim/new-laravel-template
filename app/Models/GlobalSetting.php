<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GlobalSetting extends Model
{
    use HasFactory;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'global_settings';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'typesection',
        'pptx',
        'glb',
        'png',
        'svg',
        'title',
        'status'
    ];

    /**
     * Scope a query to only include settings of a specific type section.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $typeSection
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfType($query, $typeSection)
    {
        return $query->where('typesection', $typeSection);
    }

    /**
     * Scope a query to only include active settings.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Get the file URL for a specific file type.
     *
     * @param  string  $type
     * @return string|null
     */
    public function getFileUrl($type)
    {
        if (!in_array($type, ['pptx', 'glb', 'png', 'svg'])) {
            return null;
        }

        $path = $this->{$type};
        
        if (empty($path)) {
            return null;
        }

        // Assuming files are stored in the public storage
        // Modify this logic based on your actual storage configuration
        return asset("storage/global_settings/{$type}/{$path}");
    }

    /**
     * Get all file URLs as an array.
     *
     * @return array
     */
    public function getAllFileUrls()
    {
        return [
            'pptx' => $this->getFileUrl('pptx'),
            'glb' => $this->getFileUrl('glb'),
            'png' => $this->getFileUrl('png'),
            'svg' => $this->getFileUrl('svg')
        ];
    }

    /**
     * Get a specific global setting by type section.
     *
     * @param  string  $typeSection
     * @return \App\Models\GlobalSetting|null
     */
    public static function getByType($typeSection)
    {
        return static::where('typesection', $typeSection)->first();
    }

    /**
     * Get a specific global setting by type section, but only if active.
     *
     * @param  string  $typeSection
     * @return \App\Models\GlobalSetting|null
     */
    public static function getActiveByType($typeSection)
    {
        return static::where('typesection', $typeSection)
                     ->where('status', 'active')
                     ->first();
    }
}
