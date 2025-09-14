<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'module',
        'description',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    /**
     * Get all roles that have this permission
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_permissions');
    }

    /**
     * Get all users that have this permission through roles
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_roles', 'user_id', 'role_id')
                    ->join('role_permissions', 'user_roles.role_id', '=', 'role_permissions.role_id')
                    ->where('role_permissions.permission_id', $this->id);
    }

    /**
     * Scope for active permissions
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for permissions by module
     */
    public function scopeByModule($query, string $module)
    {
        return $query->where('module', $module);
    }

    /**
     * Get permissions grouped by module
     */
    public static function getGroupedByModule()
    {
        return self::active()
            ->orderBy('module')
            ->orderBy('name')
            ->get()
            ->groupBy('module');
    }
}