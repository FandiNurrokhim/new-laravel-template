<?php

namespace App\Models;

use App\Models\Section;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;
    
    protected $table = 'products';

    protected $fillable = [
        'uuid',
        'title',
        'title_folder',
        'slug',
        'description',
        'thumbnail',
        'order_number',
        'type',
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
                'action' => 'CREATED_PRODUCT',
                'target_table' => 'products',
                'target_id' => $model->id,
                'description' => 'Product successfully created',
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
                'action' => 'UPDATED_PRODUCT',
                'target_table' => 'products',
                'target_id' => $model->id,
                'description' => 'Product successfully updated',
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
                'action' => 'DELETED_PRODUCT',
                'target_table' => 'products',
                'target_id' => $model->id,
                'description' => 'Product successfully deleted',
                'ip_address' => $ip,
                'user_agent' => $agent,
                'metadata' => json_encode([
                    'deleted' => $model->getOriginal(),
                ])
            ]);
        });
    }
    
    public function sections()
    {
        return $this->hasMany(Section::class);
    }

    public function items()
    {
        return $this->belongsToMany(Item::class, 'item_product', 'product_id', 'item_id');
    }
}