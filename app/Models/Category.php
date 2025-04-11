<?php

namespace App\Models;

use App\Models\Section;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;
    
    protected $table = 'categories';

    protected $fillable = [
        'uuid',
        'section_id',
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
                'action' => 'CREATED_CATEGORY',
                'target_table' => 'categories',
                'target_id' => $model->id,
                'description' => 'Category successfully created',
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
                'action' => 'UPDATED_CATEGORY',
                'target_table' => 'categories',
                'target_id' => $model->id,
                'description' => 'Category successfully updated',
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
                'action' => 'DELETED_CATEGORY',
                'target_table' => 'categories',
                'target_id' => $model->id,
                'description' => 'Category successfully deleted',
                'ip_address' => $ip,
                'user_agent' => $agent,
                'metadata' => json_encode([
                    'deleted' => $model->getOriginal(),
                ])
            ]);
        });
    }
    
    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function items()
    {
        return $this->belongsToMany(Item::class, 'item_category', 'category_id', 'item_id');
    }
    
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function subcategories()
    {
        return $this->hasMany(SubCategory::class, 'category_id');
    }
}