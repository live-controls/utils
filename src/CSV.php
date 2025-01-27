<?php

namespace LiveControls\Utils;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Response;

class CSV
{
    public static function exportToFile(string $fileName, Collection $modelsCollection, array|null $attributes = null): bool
    {
        $visibleFields = array_diff(
            array_keys($modelsCollection->first()->getAttributes()),
            $modelsCollection->first()->getHidden()
        );

        $file = fopen($fileName, 'w');
        fputcsv($file, is_null($attributes) ? $visibleFields : $attributes);
        foreach($modelsCollection as $model)
        {
            $row = is_null($attributes) ? $model->only($visibleFields) : $model->only($attributes);
            $row = array_map(function($value) {
                return is_array($value) ? json_encode($value) : $value;
            }, $row);
            fputcsv($file, array_values($row));
        }
        fclose($file);
        return true;
    }

    public static function exportToTemp(Collection $modelsCollection, array|null $attributes = null, string $fileName = "output.csv"): \Illuminate\Http\Response
    {
        $visibleFields = array_diff(
            array_keys($modelsCollection->first()->getAttributes()),
            $modelsCollection->first()->getHidden()
        );

        $output = fopen("php://temp", 'w');
        fputcsv($output, is_null($attributes) ? $visibleFields : $attributes);
        foreach($modelsCollection as $model)
        {
            $row = is_null($attributes) ? $model->only($visibleFields) : $model->only($attributes);
            $row = array_map(function($value) {
                return is_array($value) ? json_encode($value) : $value;
            }, $row);
            fputcsv($output, array_values($row));
        }

        rewind($output);

        $content = stream_get_contents($output);

        fclose($output);

        return Response::make($content, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="'.$fileName.'"',
        ]);
    }

    public static function exportArrayToFile(string $fileName, array $array): bool
    {
        $file = fopen($fileName, 'w');
        foreach($array as $field)
        {
            $field = array_map(function($value) {
                return is_array($value) ? json_encode($value) : $value;
            }, $field);
            fputcsv($file, $field);
        }
        fclose($file);
        return true;
    }

    public static function exportArrayToTemp(array $array, string $fileName = "output.csv"): \Illuminate\Http\Response
    {
        $output = fopen("php://temp", 'w');
        foreach($array as $field)
        {
            $field = array_map(function($value) {
                return is_array($value) ? json_encode($value) : $value;
            }, $field);
            fputcsv($output, array_values($field));
        }

        rewind($output);

        $content = stream_get_contents($output);

        fclose($output);

        return Response::make($content, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="'.$fileName.'"',
        ]);
    }
}
