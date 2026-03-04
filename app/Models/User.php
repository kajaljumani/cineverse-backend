<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = [
        'name',
        'email',
        'password',
        'last_online_at',
        'last_login_at',
        'last_logout_at',
        'is_admin',
        'is_blocked',
        'google_id',
        'fcm_token',
    ];

    protected $appends = ['avatar_url', 'is_online', 'status'];

    public function badges()
    {
        return $this->belongsToMany(Badge::class, 'user_badges')->withTimestamps();
    }

    public function preferences()
    {
        return $this->hasOne(UserPreference::class);
    }

    public function interactions()
    {
        return $this->hasMany(Interaction::class);
    }

    public function watchlist()
    {
        return $this->hasMany(Watchlist::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function following()
    {
        return $this->belongsToMany(User::class, 'follows', 'follower_id', 'following_id')->withTimestamps();
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'follows', 'following_id', 'follower_id')->withTimestamps();
    }

    public function isFollowing(User $user)
    {
        return $this->following()->where('following_id', $user->id)->exists();
    }

    public function conversations()
    {
        return Conversation::where('user_one_id', $this->id)
            ->orWhere('user_two_id', $this->id);
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function isOnline()
    {
        if (!$this->last_online_at) return false;
        // Consider online if active in last 5 minutes
        return $this->last_online_at->gt(now()->subMinutes(5));
    }

    public function getIsOnlineAttribute()
    {
        return $this->isOnline();
    }

    public function getAvatarUrlAttribute()
    {
        return "https://ui-avatars.com/api/?name=" . urlencode($this->name) . "&background=6d28d9&color=fff&size=512";
    }

    public function getStatusAttribute()
    {
        $now = now();
        $lastLogin = $this->last_login_at;
        $lastLogout = $this->last_logout_at;

        // Offline if logout is after login OR last login was more than 60 days ago OR no login yet
        if (($lastLogout && $lastLogin && $lastLogout->gt($lastLogin)) || ($lastLogin && $lastLogin->diffInDays($now) >= 60) || !$lastLogin) {
            return 'offline';
        }

        // Online if login was within 5 days
        if ($lastLogin->diffInDays($now) < 5) {
            return 'online';
        }

        // Away if login was more than 5 days ago (and hasn't explicitly logged out)
        return 'away';
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_online_at' => 'datetime',
            'last_login_at' => 'datetime',
            'last_logout_at' => 'datetime',
        ];
    }
}
