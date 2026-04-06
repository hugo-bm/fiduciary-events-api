<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Issuer
 *
 * Represents a company responsible for financial operations.
 */
class Issuer extends Model
{
    protected $fillable = ['corporate_name', 'cnpj'];

    /**
     * Get all operations for the issuer.
     */
    public function operations(): HasMany
    {
        return $this->hasMany(Operation::class);
    }
}
