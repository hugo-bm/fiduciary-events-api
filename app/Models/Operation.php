<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Operation
 *
 * Represents a financial operation linked to an issuer.
 */
class Operation extends Model
{
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

    /**
     * Get obligations for the operation.
     */
    public function obligations(): HasMany {
        return $this->hasMany(Obligation::class);
    }
}
