<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GraveCleaningRequest extends Model
{
    use HasFactory;

    protected $table = 'grave_cleaning_request';

    protected $fillable = [
        'requester_name',
        'price',
        'address',
        'phone_number',
        'proof_photo',
        'rt',
        'rw',
        'dusun',
        'grave_location_id',
        'payment_status',
        'work_status',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }

    public function location()
    {
        return $this->belongsTo(GraveLocation::class, 'grave_location_id');
    }
}
