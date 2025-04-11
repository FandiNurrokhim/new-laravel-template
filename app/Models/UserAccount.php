<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserAccount extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_accounts';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'roles',
        'is_active',
        'refresh_token',
        'email_verified_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'refresh_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'refresh_token' => 'datetime',
        'is_active' => 'boolean',
    ];
    
    /**
     * Get the profile associated with the user account.
     */
    public function profile()
    {
        return $this->hasOne(UserProfile::class, 'account_id');
    }

    /**
     * Get the logs created by the user account.
     */
    public function logs()
    {
        return $this->hasMany(Log::class, 'created_by');
    }

    /**
     * Get the addin accesses for the user account.
     */
    public function addinAccesses()
    {
        return $this->hasMany(AddinAccessUser::class, 'account_id');
    }

    /**
     * Get only the active addin accesses for the user account.
     */
    public function activeAddinAccesses()
    {
        return $this->addinAccesses()->active();
    }

    /**
     * Get only the current (not expired) addin accesses for the user account.
     */
    public function currentAddinAccesses()
    {
        return $this->addinAccesses()->active()->current();
    }

    
    /**
     * Get the wishlists for the user account.
     */
    public function wishlists()
    {
        return $this->hasMany(AddinWishlist::class, 'account_id');
    }
    
    /**
     * Get the default wishlist for the user account or create one if it doesn't exist.
     *
     * @return \App\Models\AddinWishlist
     */
    public function getDefaultWishlistAttribute()
    {
        return $this->wishlists()->firstOrCreate(
            ['account_id' => $this->id],
            ['title' => 'My Wishlist']
        );
    }
}
