<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\ActivityLog;

class UserProfile extends Model
{
    use HasFactory;

    protected $table = 'user_profiles';

    protected $fillable = [
        'uuid',
        'user_id',
        'first_name',
        'last_name',
        'gender',
        'birth_date',
        'phone_number',
        'profile_picture_url',
        'bio',
        'ip_address',
        'country',
        'city',
        'latitude',
        'longitude',
        'timezone',
        'language_preference',
        'last_active',
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
                'action' => 'CREATED_USER_PROFILE',
                'target_table' => 'user_profiles',
                'target_id' => $model->id,
                'description' => 'Profile created for user_id: ' . $model->user_id,
                'ip_address' => $ip,
                'user_agent' => $agent,
                'metadata' => json_encode([
                    'new' => $model->getAttributes(),
                ])
            ]);
        });

        static::updated(function ($model) {
            $ip = request() ? request()->ip() : null;
            $agent = request() ? request()->userAgent() : null;
            $changes = $model->getChanges();
            $original = $model->getOriginal();

            ActivityLog::create([
                'user_id' => auth()->check() ? auth()->id() : null,
                'action' => 'UPDATED_USER_PROFILE',
                'target_table' => 'user_profiles',
                'target_id' => $model->id,
                'description' => 'Profile updated for user_id: ' . $model->user_id,
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
                'action' => 'DELETED_USER_PROFILE',
                'target_table' => 'user_profiles',
                'target_id' => $model->id,
                'description' => 'Profile deleted for user_id: ' . $model->user_id,
                'ip_address' => $ip,
                'user_agent' => $agent,
            ]);
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
