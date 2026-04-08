<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Enums\UserRoleEnum;

/**
 * Class Obligation
 *
 * Represents a compliance obligation with deadline tracking.
 */
class Obligation extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'operation_id',
        'title',
        'due_date',
        'status',
        'delivered_at'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'due_date' => 'date',
        'delivered' => 'date',
        ];
    }

    /**
     * Scope to filter obligations visible to a given user
     *
     * @param $query
     * @param \App\Models\User $user
     * @return mixed
     */
    public function scopeVisibleTo($query, $user)
    {
         $userRole = UserRoleEnum::from($user->role);
        if ($userRole === UserRoleEnum::ANALYST) {
            return $query->whereHas('operation.analysts', function ($q) use ($user) {
                $q->where('users.id', $user->id);
            });
        }

        return $query;
    }

    /**
     * Get the operation that owns the obligation.
     */
    public function operation(): BelongsTo
    {
        return $this->belongsTo(Operation::class);
    }
}
