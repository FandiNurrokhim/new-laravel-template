<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GraveLocation extends Model
{
    use HasFactory;
    protected $fillable = [
        'grave_group_id',
        'location_name',
        'is_available',
    ];

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
                'action' => 'CREATED_LOCATION',
                'target_table' => 'grave_locations',
                'target_id' => $model->id,
                'description' => 'New grave location created: ' . $model->name,
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
                'action' => 'UPDATED_LOCATION',
                'target_table' => 'grave_locations',
                'target_id' => $model->id,
                'description' => 'Grave location updated: ' . $model->name,
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
                'action' => 'DELETED_LOCATION',
                'target_table' => 'grave_locations',
                'target_id' => $model->id,
                'description' => 'Grave location deleted: ' . $model->name,
                'ip_address' => $ip,
                'user_agent' => $agent,
            ]);
        });
    }

    public function group()
    {
        return $this->belongsTo(GraveGroup::class, 'grave_group_id');
    }

    public function request()
    {
        return $this->hasOne(GraveRequest::class);
    }

    public function detail()
    {
        return $this->hasOne(GraveDetail::class);
    }
}
