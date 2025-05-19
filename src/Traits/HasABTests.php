<?php

namespace LiveControls\Utils\Traits;

use LiveControls\Utils\AB;

trait HasABTests
{
    /**
     * Check if the current Model is inside the selected variant based on their primaryKey. Use the salt to have different outcomes for different tests
     *
     * @param integer|string $variant
     * @param string $salt
     * @return boolean
     */
    public function hasVariant(int|string $variant, string $salt = ""): bool
    {
        return AB::hasVariant($this, $variant, $salt);
    }
    
    /**
     * Gets the variant for the current model (Either 1 or 2) based on their primaryKey. Use the salt to have different outcomes for different tests
     *
     * @param Model|int $model
     * @param string $salt
     * @return integer
     */
    public function getVariantForModel(string $salt): int
    {
        return AB::getVariantForModel($this, $salt);
    }

    /**
     * Checks if the model is part of the A test variant
     *
     * @return boolean
     */
    public function getHasVariantAAttribute(): bool
    {
        return AB::hasVariant($this, 1);
    }

    
    /**
     * Checks if the model is part of the B test variant
     *
     * @return boolean
     */
    public function getHasVariantBAttribute(): bool
    {
        return AB::hasVariant($this, 2);
    }
} 