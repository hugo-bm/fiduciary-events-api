<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Enums\OperationTypeEnum;

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

    protected function casts(): array
    {
        return [
            'operation_type' => OperationTypeEnum::class,
        ];
    }
    /**
     * Get obligations for the operation.
     */
    public function obligations(): HasMany {
        return $this->hasMany(Obligation::class);
    }
}
