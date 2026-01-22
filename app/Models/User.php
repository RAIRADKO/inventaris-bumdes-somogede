<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'business_unit_id',
        'is_active',
        'phone',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public const ROLE_LABELS = [
        'director' => 'Direktur',
        'treasurer' => 'Bendahara',
        'unit_admin' => 'Admin Unit',
        'supervisor' => 'Pengawas',
        'accountant' => 'Akuntan',
    ];

    public function businessUnit(): BelongsTo
    {
        return $this->belongsTo(BusinessUnit::class);
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function approvals(): HasMany
    {
        return $this->hasMany(Approval::class);
    }

    // Role check methods
    public function isDirector(): bool
    {
        return $this->role === 'director';
    }

    public function isTreasurer(): bool
    {
        return $this->role === 'treasurer';
    }

    public function isUnitAdmin(): bool
    {
        return $this->role === 'unit_admin';
    }

    public function isSupervisor(): bool
    {
        return $this->role === 'supervisor';
    }

    public function isAccountant(): bool
    {
        return $this->role === 'accountant';
    }

    // Permission check methods
    public function canApprove(): bool
    {
        return in_array($this->role, ['director', 'treasurer']);
    }

    public function canEditTransactions(): bool
    {
        return in_array($this->role, ['director', 'treasurer', 'unit_admin', 'accountant']);
    }

    public function canViewAllData(): bool
    {
        return in_array($this->role, ['director', 'supervisor', 'accountant']);
    }

    public function canManageJournals(): bool
    {
        return in_array($this->role, ['director', 'accountant']);
    }

    public function canManageUsers(): bool
    {
        return $this->role === 'director';
    }

    public function getRoleLabelAttribute(): string
    {
        return self::ROLE_LABELS[$this->role] ?? $this->role;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
