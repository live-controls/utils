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
            fputcsv($file, is_null($attributes) ? $model->only($visibleFields) : array_values($model->only($attributes)));
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
            fputcsv($output, is_null($attributes) ? $model->only($visibleFields) : array_values($model->only($attributes)));
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
