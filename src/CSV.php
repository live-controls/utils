<?php

namespace LiveControls\Utils;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Response;

class CSV
{
    /**
     * Imports CSV from a file and returns an array with headers as first line
     *
     * @param string $fileName
     * @return array
     */
    public static function importCSV(string $fileName)
    {
        $csv = array_map('str_getcsv', file($fileName));
        array_walk($csv, function(&$a) use ($csv) {
            $a = array_combine($csv[0], $a);
        });
        array_shift($csv);
        return $csv;
    }
    
    /**
     * Exports an array of data [['Max', '10'], ['Peter', '14']] to a valid CSV string
     *
     * @param array $data
     * @param string $separator
     * @param string $lineEnding
     *
     * @return string
     */
    public static function exportCSV(array $data, string $separator = ",", string $lineEnding = "\n"): string
    {
        $csvString = '';
        foreach($data as $row)
        {
            $line = '';
            foreach($row as $column){
                if($line != ''){
                    $line .= $separator;
                }
                $line .= $column;
            }
            $csvString .= $line.$lineEnding;
        }
        return $csvString;
    }

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
        $header = array_keys($array[0]);
        fputcsv($file, $header);
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
        $header = array_keys($array[0]);
        fputcsv($output, $header);
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
