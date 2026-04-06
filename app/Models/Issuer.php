<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
    public function operations(): hasMany
    {
        return $this->hasMany(Operation::class);
    }
}
