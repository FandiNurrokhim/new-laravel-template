<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'logs';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'action',
        'title',
        'description',
        'endpoint',
        'payload',
        'status',
        'messages',
        'created_by'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'action' => 'string',
        'status' => 'string',
        'payload' => 'array',
        'created_at' => 'datetime',
    ];

    /**
     * The allowed values for the action field.
     *
     * @var array<int, string>
     */
    public static $actionTypes = [
        'CREATED',
        'UPDATED',
        'DELETED',
        'LOGIN',
        'LOGOUT',
        'FAILED_LOGIN',
        'OTHER'
    ];

    /**
     * The allowed values for the status field.
     *
     * @var array<int, string>
     */
    public static $statusTypes = [
        'SUCCESS',
        'FAILED'
    ];

    /**
     * Get the user that created the log.
     */
    public function creator()
    {
        return $this->belongsTo(UserAccount::class, 'created_by');
    }

    /**
     * Scope a query to only include logs with a specific action.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $action
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfAction($query, $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope a query to only include logs with a specific status.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $status
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to only include logs created by a specific user.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $userId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('created_by', $userId);
    }
}
