<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
        'last_login_at'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
        'last_login_at' => 'datetime'
    ];

    /**
     * Get all roles assigned to this user
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_roles')
                    ->withPivot(['assigned_by', 'assigned_at', 'expires_at', 'is_active'])
                    ->withTimestamps();
    }

    /**
     * Get all permissions for this user through roles
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'user_roles', 'user_id', 'role_id')
                    ->join('role_permissions', 'user_roles.role_id', '=', 'role_permissions.role_id')
                    ->where('user_roles.is_active', true)
                    ->where('roles.is_active', true)
                    ->where('permissions.is_active', true)
                    ->where(function($query) {
                        $query->whereNull('user_roles.expires_at')
                              ->orWhere('user_roles.expires_at', '>', now());
                    });
    }

    /**
     * Check if user has specific role
     */
    public function hasRole(string $role): bool
    {
        return $this->roles()
                    ->where('slug', $role)
                    ->where('is_active', true)
                    ->where(function($query) {
                        $query->whereNull('user_roles.expires_at')
                              ->orWhere('user_roles.expires_at', '>', now());
                    })
                    ->exists();
    }

    /**
     * Check if user has specific permission
     */
    public function hasPermission(string $permission): bool
    {
        return $this->permissions()
                    ->where('slug', $permission)
                    ->exists();
    }

    /**
     * Check if user has any of the given roles
     */
    public function hasAnyRole(array $roles): bool
    {
        return $this->roles()
                    ->whereIn('slug', $roles)
                    ->where('is_active', true)
                    ->where(function($query) {
                        $query->whereNull('user_roles.expires_at')
                              ->orWhere('user_roles.expires_at', '>', now());
                    })
                    ->exists();
    }

    /**
     * Check if user has all of the given roles
     */
    public function hasAllRoles(array $roles): bool
    {
        $userRoles = $this->roles()
                          ->where('is_active', true)
                          ->where(function($query) {
                              $query->whereNull('user_roles.expires_at')
                                    ->orWhere('user_roles.expires_at', '>', now());
                          })
                          ->pluck('slug')
                          ->toArray();

        return count(array_intersect($roles, $userRoles)) === count($roles);
    }

    /**
     * Check if user has any of the given permissions
     */
    public function hasAnyPermission(array $permissions): bool
    {
        return $this->permissions()
                    ->whereIn('slug', $permissions)
                    ->exists();
    }

    /**
     * Assign role to user
     */
    public function assignRole(Role $role, ?User $assignedBy = null, ?\DateTime $expiresAt = null): void
    {
        $this->roles()->syncWithoutDetaching([
            $role->id => [
                'assigned_by' => $assignedBy?->id,
                'assigned_at' => now(),
                'expires_at' => $expiresAt,
                'is_active' => true
            ]
        ]);
    }

    /**
     * Remove role from user
     */
    public function removeRole(Role $role): void
    {
        $this->roles()->detach($role->id);
    }

    /**
     * Sync roles for user
     */
    public function syncRoles(array $roleIds): void
    {
        $this->roles()->sync($roleIds);
    }

    /**
     * Get user's active roles
     */
    public function getActiveRoles()
    {
        return $this->roles()
                    ->where('is_active', true)
                    ->where(function($query) {
                        $query->whereNull('user_roles.expires_at')
                              ->orWhere('user_roles.expires_at', '>', now());
                    })
                    ->get();
    }

    /**
     * Get user's active permissions
     */
    public function getActivePermissions()
    {
        return $this->permissions()->get();
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('admin') || $this->hasRole('super-admin');
    }

    /**
     * Check if user is active
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }

    /**
     * Scope for active users
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for users with specific role
     */
    public function scopeWithRole($query, string $role)
    {
        return $query->whereHas('roles', function($q) use ($role) {
            $q->where('slug', $role)
              ->where('is_active', true);
        });
    }
}