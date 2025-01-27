<?php

namespace LiveControls\Utils;

class CSV
{
    public static function exportToFile($fileName, string $model, string $whereField = "", string $whereSign = "=", string $whereValue = "", $attributes = null): bool
    {
        $model = new $model();
        if(!is_null($attributes)){
            $models = $model->where($whereField, $whereSign, $whereValue)->get($attributes);
        }else{
            $models = $model->where($whereField, $whereSign, $whereValue)->get();
        }
        $file = fopen($fileName, 'w');
        fputcsv($file, array_keys($models[0]->getAttributes()));
        foreach($models as $model)
        {
            fputcsv($file, array_values($model->getAttributes()));
        }
        fclose($file);
        return true;
    }
}