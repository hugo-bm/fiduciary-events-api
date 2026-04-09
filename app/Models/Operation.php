<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Enums\OperationTypeEnum;
use App\Enums\UserRoleEnum;

/**
 * Class Operation
 *
 * Represents a financial operation linked to an issuer.
 */
class Operation extends Model
{
    use HasFactory;

    protected $fillable = [
        'issue_id',
        'asset_code',
        'operation_type'
    ];

    /**
     * Get the issuer that owns the operation.
     */
    public function issue(): BelongsTo {
        return $this->belongsTo(Issuer::class);
    }

    public function analysts()
    {
        return $this->belongsToMany(
            User::class,
            'operation_user',
            'operation_id',
            'user_id'
        );
    }

    protected function casts(): array
    {
        return [
            'operation_type' => OperationTypeEnum::class,
        ];
    }

    /**
     * Scope to filter operations visible to a given user
     *
     * @param $query
     * @param \App\Models\User $user
     * @return mixed
     */
    public function scopeVisibleTo($query, $user)
    {
        $userRole = UserRoleEnum::from($user->role);
        if ($userRole === UserRoleEnum::ANALYST) {
            return $query->whereHas('analysts', function ($q) use ($user) {
                $q->where('users.id', $user->id);
            });
        }

        return $query;
    }

    /**
     * Get obligations for the operation.
     */
    public function obligations(): HasMany {
        return $this->hasMany(Obligation::class);
    }
}
