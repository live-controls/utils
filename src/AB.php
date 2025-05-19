<?php

namespace LiveControls\Utils;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class AB
{
    /**
     * Gets the variant for the model (Either 1 or 2) based on their primaryKey. Use the salt to have different outcomes for different tests
     *
     * @param Model|int $model
     * @param string $salt
     * @return integer
     */
    public static function getVariantForModel(Model|int $model, string $salt = ""): int
    {
        $modelId = !is_int($model) ? $model->getKey() : $model;
        return Cache::remember("ab_variant_{$modelId}_{$salt}", now()->endOfDay(), function() use($modelId, $salt){
            $crc = crc32($modelId.$salt);
            return $crc % 2 === 0 ? 1 : 2;
        });
    }

    /**
     * Check if the Model is inside the selected variant based on their primaryKey. Use the salt to have different outcomes for different tests
     *
     * @param Model|int $model
     * @param integer|string $variant
     * @param string $salt
     * @return boolean
     */
    public static function hasVariant(Model|int $model, int|string $variant, string $salt = ""): bool
    {
        if(is_string($variant)){
            if(strtolower($variant) == "a"){
                $variant = 1;
            }elseif(strtolower($variant) == "b"){
                $variant = 2;
            }
        }
        if($variant != 1 && $variant != 2){
            throw new Exception("Invalid variant {$variant}, needs to be either 1/A or 2/B!");
        }
        return self::getVariantForModel($model, $salt) === $variant;
    }
}