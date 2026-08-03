<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Every role reaches the panel; what they can do once inside is decided
     * by the policies in app/Policies. This has to be declared explicitly:
     * Filament's Authenticate middleware only lets a user model that does not
     * implement FilamentUser through when APP_ENV is "local", so without this
     * every login on production is rejected with a 403.
     *
     * Listing the roles rather than returning true also means a user whose
     * role is empty or unrecognised is refused instead of let in by default.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return in_array($this->role, ['super_admin', 'admin', 'editor', 'commenter'], true);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

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
        ];
    }

    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class);
    }

    /**
     * Four-tier role hierarchy:
     *   super_admin — unrestricted; the only role that can manage other admins/super_admins.
     *   admin       — full content management + manages editor/commenter users.
     *   editor      — full content management; no access to Users or Site/Nav Settings.
     *   commenter   — read-only everywhere.
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isEditor(): bool
    {
        return $this->role === 'editor';
    }

    public function isCommenter(): bool
    {
        return $this->role === 'commenter';
    }

    /** Super admins and admins — full content access + user management (with restrictions, see UserPolicy). */
    public function isAdminTier(): bool
    {
        return in_array($this->role, ['super_admin', 'admin'], true);
    }

    /** Anyone who can create/edit/delete content: super_admin, admin, editor. */
    public function canManageContent(): bool
    {
        return in_array($this->role, ['super_admin', 'admin', 'editor'], true);
    }

    /** Whether this user outranks another for user-management purposes. */
    public function outranks(User $other): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        // Admins may only manage editor/commenter accounts, never other
        // admins or super_admins.
        return $this->isAdmin() && ! $other->isAdminTier();
    }
}
