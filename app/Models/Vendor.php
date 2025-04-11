<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\ActivityLog;

class Vendor extends Model
{
    use HasFactory;

    protected $table = 'vendors';

    protected $fillable = [
        'uuid',
        'title',
        'description',
        'thumbnail',
        'status',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }

            if (auth()->check()) {
                $model->created_by = auth()->id();
                $model->updated_by = auth()->id();
            }
        });

        static::updating(function ($model) {
            if (auth()->check()) {
                $model->updated_by = auth()->id();
            }
        });

        static::created(function ($model) {
            $ip = request()?->ip();
            $agent = request()?->userAgent();

            ActivityLog::create([
                'user_id' => auth()->check() ? auth()->id() : null,
                'action' => 'CREATED_VENDOR',
                'target_table' => 'vendors',
                'target_id' => $model->id,
                'description' => 'Vendor successfully created',
                'ip_address' => $ip,
                'user_agent' => $agent,
                'metadata' => json_encode([
                    'new' => $model->getAttributes(),
                ])
            ]);
        });

        static::updated(function ($model) {
            $ip = request()?->ip();
            $agent = request()?->userAgent();
            $changes = $model->getChanges();
            $original = $model->getOriginal();

            ActivityLog::create([
                'user_id' => auth()->check() ? auth()->id() : null,
                'action' => 'UPDATED_VENDOR',
                'target_table' => 'vendors',
                'target_id' => $model->id,
                'description' => 'Vendor successfully updated',
                'ip_address' => $ip,
                'user_agent' => $agent,
                'metadata' => json_encode([
                    'old' => $original,
                    'changes' => $changes,
                ])
            ]);
        });

        static::deleted(function ($model) {
            $ip = request()?->ip();
            $agent = request()?->userAgent();

            ActivityLog::create([
                'user_id' => auth()->check() ? auth()->id() : null,
                'action' => 'DELETED_VENDOR',
                'target_table' => 'vendors',
                'target_id' => $model->id,
                'description' => 'Vendor successfully deleted',
                'ip_address' => $ip,
                'user_agent' => $agent,
                'metadata' => json_encode([
                    'deleted' => $model->getOriginal(),
                ])
            ]);
        });
    }
}
