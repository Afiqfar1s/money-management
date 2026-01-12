<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

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
        'permissions',
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
            'permissions' => 'array',
        ];
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user has a specific permission
     */
    public function hasPermission(string $permission): bool
    {
        if ($this->isAdmin()) {
            return true; // Admins have all permissions
        }

        $permissions = $this->permissions ?? [];
        return in_array($permission, $permissions);
    }

    /**
     * Get default permissions for regular users
     */
    public static function getDefaultPermissions(): array
    {
        return [
            'view_own_debtors',
            'create_debtors',
            'edit_own_debtors',
            'delete_own_debtors',
            'manage_payments',
            'manage_adjustments',
        ];
    }

    /**
     * Get all available permissions
     */
    public static function getAllPermissions(): array
    {
        return [
            'view_own_debtors' => 'View Own Debtors',
            'view_all_debtors' => 'View All Debtors',
            'create_debtors' => 'Create Debtors',
            'edit_own_debtors' => 'Edit Own Debtors',
            'edit_all_debtors' => 'Edit All Debtors',
            'delete_own_debtors' => 'Delete Own Debtors',
            'delete_all_debtors' => 'Delete All Debtors',
            'manage_payments' => 'Manage Payments',
            'manage_adjustments' => 'Manage Balance Adjustments',
            'view_reports' => 'View Reports',
            'export_data' => 'Export Data',
        ];
    }

    /**
     * Relationship: User has many debtors
     */
    public function debtors()
    {
        return $this->hasMany(Debtor::class);
    }

    /**
     * Companies this user can access (Company Representative access)
     */
    public function companies(): BelongsToMany
    {
        return $this->belongsToMany(Company::class)
            ->withTimestamps()
            ->withPivot(['role']);
    }
}
