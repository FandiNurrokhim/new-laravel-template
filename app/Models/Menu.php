<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'parent_id',
        'header',
        'title',
        'icon',
        'route'
    ];

    public function parent()
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Menu::class, 'parent_id');
    }

    // public function getPermissionLikeAttribute()
    // {
    //     if (!$this->route) {
    //         return null;
    //     }
    //     $prefix = explode('.', $this->route)[0];
    //     return Permission::where('name', $prefix)->first();
    // }

    public function getPermissionLikeAttribute()
    {
        if (!$this->route) {
            return null;
        }

        $segments = explode('.', $this->route);
        if (!isset($segments[0])) {
            return null;
        }

        $prefix = $segments[0];
        return Permission::where('name', $prefix)->first();
    }

}
