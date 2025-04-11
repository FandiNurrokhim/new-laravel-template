<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TokenBlocklist extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'token_blocklists';

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
        'jti'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime'
    ];

    /**
     * Check if a token with the given JTI is in the blocklist.
     *
     * @param  string  $jti
     * @return bool
     */
    public static function isBlocked($jti)
    {
        return static::where('jti', $jti)->exists();
    }

    /**
     * Add a token to the blocklist.
     *
     * @param  string  $jti
     * @return \App\Models\TokenBlocklist
     */
    public static function addToBlocklist($jti)
    {
        if (!static::isBlocked($jti)) {
            return static::create(['jti' => $jti]);
        }

        return static::where('jti', $jti)->first();
    }

    /**
     * Remove old tokens from the blocklist.
     *
     * @param  int  $days
     * @return int
     */
    public static function purgeOldTokens($days = 30)
    {
        return static::where('created_at', '<', now()->subDays($days))->delete();
    }
}