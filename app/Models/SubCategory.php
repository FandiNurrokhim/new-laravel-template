<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubCategory extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sub_categories';

    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'uuid',
        'category_id',
        'title',
        'slug',
        'description',
        'thumbnail',
        'order_number',
        'status',
        'created_by',
        'updated_by'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
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
                'action' => 'CREATED_SUB_CATEGORY',
                'target_table' => 'sub_categories',
                'target_id' => $model->id,
                'description' => 'New sub-category created: ' . $model->title,
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
                'action' => 'UPDATED_SUB_CATEGORY',
                'target_table' => 'sub_categories',
                'target_id' => $model->id,
                'description' => 'Sub-category updated: ' . $model->title,
                'ip_address' => $ip,
                'user_agent' => $agent,
                'metadata' => json_encode([
                    'old' => $original,
                    'changes' => $changes,
                ]),
            ]);
        });

        static::deleted(function ($model) {
            $ip = request() ? request()->ip() : null;
            $agent = request() ? request()->userAgent() : null;

            ActivityLog::create([
                'user_id' => auth()->check() ? auth()->id() : null,
                'action' => 'DELETED_SUB_CATEGORY',
                'target_table' => 'sub_categories',
                'target_id' => $model->id,
                'description' => 'Sub-category deleted: ' . $model->title,
                'ip_address' => $ip,
                'user_agent' => $agent,
            ]);
        });
    }

    /**
     * Get the category that owns the sub-category.
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function itemSubCategory()
    {
        return $this->hasMany(ItemSubCategory::class, 'sub_category_id');
    }

    public function items()
    {
        return $this->belongsToMany(Item::class, 'item_sub_category', 'sub_category_id', 'item_id');
    }


    /**
     * Get the user who created the sub-category.
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated the sub-category.
     */
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
