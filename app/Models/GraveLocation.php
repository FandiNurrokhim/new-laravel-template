<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GraveLocation extends Model
{
    use HasFactory;
    protected $fillable = [
        'code',
        'grave_group_id',
        'order',
        'location_name',
        'is_confirmed',
        'is_reserved',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }

            if (empty($model->code)) {
                $lastLocation = self::orderBy('id', 'desc')
                    ->first();

                $nextNumber = 1;
                if ($lastLocation && preg_match('/A(\d+)/', $lastLocation->code, $matches)) {
                    $nextNumber = intval($matches[1]) + 1;
                }

                do {
                    $newCode = 'A' . str_pad($nextNumber, 2, '0', STR_PAD_LEFT);
                    $exists = self::where('grave_group_id', $model->grave_group_id)
                        ->where('code', $newCode)
                        ->exists();
                    $nextNumber++;
                } while ($exists);

                $model->code = $newCode;
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

    public function cleaningRequest()
    {
        return $this->hasOne(GraveCleaningRequest::class);
    }

    public function corpseDetail()
    {
        return $this->hasOne(CorpseDetail::class, 'grave_location_id', 'id');
    }

    public function getCorpsesCount()
    {
        return $this->hasMany(CorpseDetail::class)->where('grave_location_id', $this->id)->count();
    }

    public function isUsed()
    {
        return $this->corpseDetail()->exists() || $this->request()->exists();
    }
}
