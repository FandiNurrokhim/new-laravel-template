<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CorpseDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'grave_location_id',
        'name',
        'photo',
        'birth_date',
        'birth_place',
        'age',
        'death_date',
        'javanese_day',
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
                'action' => 'CREATED_DETAIL',
                'target_table' => 'grave_details',
                'target_id' => $model->id,
                'description' => 'New grave detail created: ' . $model->name,
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
                'action' => 'UPDATED_DETAIL',
                'target_table' => 'grave_details',
                'target_id' => $model->id,
                'description' => 'Grave detail updated: ' . $model->name,
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
                'action' => 'DELETED_DETAIL',
                'target_table' => 'grave_details',
                'target_id' => $model->id,
                'description' => 'Grave detail deleted: ' . $model->name,
                'ip_address' => $ip,
                'user_agent' => $agent,
            ]);
        });
    }

    public function getBirthDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format('d-m-Y') : null;
    }
    public function getDeathDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format('d-m-Y') : null;
    }

    public function location()
    {
        return $this->belongsTo(GraveLocation::class, 'grave_location_id', 'id');
    }

    public function group()
    {
        return $this->hasOneThrough(GraveGroup::class, GraveLocation::class, 'id', 'id', 'grave_location_id', 'grave_group_id');
    }
}
