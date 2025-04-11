<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Section extends Model
{
    use HasFactory;

    protected $table = 'sections';

    protected $fillable = [
        'uuid',
        'product_id',
        'title',
        'slug',
        'description',
        'thumbnail',
        'order_number',
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
                'action' => 'CREATED_SECTION',
                'target_table' => 'sections',
                'target_id' => $model->id,
                'description' => 'Section successfully created',
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
                'action' => 'UPDATED_SECTION',
                'target_table' => 'sections',
                'target_id' => $model->id,
                'description' => 'Section successfully updated',
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
                'action' => 'DELETED_SECTION',
                'target_table' => 'sections',
                'target_id' => $model->id,
                'description' => 'Section successfully deleted',
                'ip_address' => $ip,
                'user_agent' => $agent,
                'metadata' => json_encode([
                    'deleted' => $model->getOriginal(),
                ])
            ]);
        });
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function items()
    {
        return $this->belongsToMany(Item::class, 'item_section', 'section_id', 'item_id');
    }
}
