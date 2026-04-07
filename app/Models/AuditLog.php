<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
/**
 * Class AuditLog
 *
 * Represents an immutable audit log entry.
 *
 * @property int $id
 * @property int $user_id
 * @property string $action
 * @property array $details
 * @property \Illuminate\Support\Carbon $created_at
 */
class AuditLog extends Model
{
    /**
     * Disable default timestamps (we only use created_at)
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Mass assignable attributes
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'action',
        'details',
    ];

    /**
     * Attribute casting
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'details' => 'array',
            'created_at' => 'datetime',
        ];
    }

    /**
     * Boot method to enforce immutability
     *
     * @return void
     */
    protected static function booted(): void
    {
        static::updating(function () {
            throw new \LogicException('Audit logs are immutable and cannot be updated.');
        });

        static::deleting(function () {
            throw new \LogicException('Audit logs cannot be deleted.');
        });
    }

    /**
     * Relationship with User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
