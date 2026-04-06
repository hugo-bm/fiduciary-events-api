<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Obligation
 *
 * Represents a compliance obligation with deadline tracking.
 */
class Obligation extends Model
{
    protected $fillable = [
        'operation_id',
        'title',
        'due_date',
        'status',
        'delivered_at'
    ];

    protected $cast = [
        'due_date' => 'date',
        'delivered' => 'date',
    ];

    /**
     * Get the operation that owns the obligation.
     */
    public function operation(): BelongsTo
    {
        return $this->belongsTo(Operation::class);
    }
}
