<?php

namespace App\Support;

use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Streams a CSV download. Rows are streamed rather than buffered so large
 * exports don't eat memory, and a UTF-8 BOM is written first so Excel opens
 * Bengali text and the ৳ sign correctly.
 */
class Csv
{
    public static function download(string $filename, array $headings, iterable $rows): StreamedResponse
    {
        $filename = str_ends_with($filename, '.csv') ? $filename : $filename.'.csv';

        return response()->streamDownload(function () use ($headings, $rows) {
            $handle = fopen('php://output', 'w');

            fwrite($handle, "\xEF\xBB\xBF"); // BOM for Excel
            fputcsv($handle, $headings);

            foreach ($rows as $row) {
                fputcsv($handle, array_map(fn ($v) => is_scalar($v) || $v === null ? $v : (string) $v, (array) $row));
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
