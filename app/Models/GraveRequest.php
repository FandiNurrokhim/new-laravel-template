<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GraveRequest extends Model
{
    use HasFactory;
    protected $fillable = [
        'requester_name',
        'address',
        'phone_number',
        'rt',
        'rw',
        'dusun',
        'corpse_name',
        'grave_location_id',
        'status',
        'notes',
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
                'action' => 'CREATED_REQUEST',
                'target_table' => 'grave_requests',
                'target_id' => $model->id,
                'description' => 'New grave request created: ' . $model->name,
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
                'action' => 'UPDATED_REQUEST',
                'target_table' => 'grave_requests',
                'target_id' => $model->id,
                'description' => 'Grave request updated: ' . $model->name,
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
                'action' => 'DELETED_REQUEST',
                'target_table' => 'grave_requests',
                'target_id' => $model->id,
                'description' => 'Grave request deleted: ' . $model->name,
                'ip_address' => $ip,
                'user_agent' => $agent,
            ]);
        });
    }

    public function location()
    {
        return $this->belongsTo(GraveLocation::class, 'grave_location_id');
    }
}
