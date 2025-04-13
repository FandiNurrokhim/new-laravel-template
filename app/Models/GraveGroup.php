<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GraveGroup extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'max_graves', 'unused_graves', 'used_graves', 'is_full'];


    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });

        static::created(function ($model) {
            $ip = request() ? request()->ip() : null;
            $agent = request() ? request()->userAgent() : null;

            ActivityLog::create([
                'user_id' => auth()->check() ? auth()->id() : null,
                'action' => 'CREATED_GROUP',
                'target_table' => 'grave_groups',
                'target_id' => $model->id,
                'description' => 'New grave group created: ' . $model->name,
                'ip_address' => $ip,
                'user_agent' => $agent,
                'metadata' => json_encode([
                    'new' => $model->getAttributes(),
                ]),
            ]);
        });

        static::updated(function ($model) {
            $ip = request() ? request()->ip() : null;
            $agent = request() ? request()->userAgent() : null;
            $changes = $model->getChanges();
            $original = $model->getOriginal();

            ActivityLog::create([
                'user_id' => auth()->check() ? auth()->id() : null,
                'action' => 'UPDATED_GROUP',
                'target_table' => 'grave_groups',
                'target_id' => $model->id,
                'description' => 'Grave group updated: ' . $model->name,
                'ip_address' => $ip,
                'user_agent' => $agent,
                'metadata' => json_encode([
                    'old' => $original,
                    'changes' => $changes,
                ])
            ]);
        });

        static::deleted(function ($model) {
            $ip = request() ? request()->ip() : null;
            $agent = request() ? request()->userAgent() : null;

            ActivityLog::create([
                'user_id' => auth()->check() ? auth()->id() : null,
                'action' => 'DELETED_GROUP',
                'target_table' => 'grave_groups',
                'target_id' => $model->id,
                'description' => 'Grave group deleted: ' . $model->name,
                'ip_address' => $ip,
                'user_agent' => $agent,
            ]);
        });
    }

    public function locations()
    {
        return $this->hasMany(GraveLocation::class);
    }
}
