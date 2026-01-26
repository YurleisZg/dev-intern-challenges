<?php

namespace App\Features\Elkin\SalaryCalculator\Services;

use App\Features\Elkin\SalaryCalculator\Models\SalaryRecord;

class SalaryExportService
{
    /**
     * Export salary record to CSV format
     */
    public static function exportRecordAsCSV(SalaryRecord $record)
    {
        $filename = 'salary_record_' . $record->record_id . '_' . date('Ymd_His') . '.csv';

        $callback = function () use ($record) {
            $file = fopen('php://output', 'w');

            // Header row
            fputcsv($file, ['Salary Calculation Export']);
            fputcsv($file, []);

            // Record info
            fputcsv($file, ['Record ID', $record->record_id]);
            fputcsv($file, ['Employee ID', $record->user_id]);
            fputcsv($file, ['Gross Salary', '$' . number_format($record->gross_salary_input, 2)]);
            fputcsv($file, ['Date Created', $record->created_at->format('M j, Y H:i')]);
            fputcsv($file, ['Status', ucfirst($record->status)]);
            fputcsv($file, []);

            // Overtime details header
            fputcsv($file, ['Overtime Details']);
            fputcsv($file, ['Date', 'Start Time', 'End Time']);

            // Overtime data
            foreach ($record->details as $detail) {
                fputcsv($file, [
                    $detail->shift_date,
                    $detail->start_time,
                    $detail->end_time
                ]);
            }

            fclose($file);
        };

        return [$filename, $callback];
    }

    /**
     * Export multiple records to CSV
     */
    public static function exportMultipleRecordsAsCSV($records)
    {
        $filename = 'salary_records_export_' . date('Ymd_His') . '.csv';

        $callback = function () use ($records) {
            $file = fopen('php://output', 'w');

            // Header
            fputcsv($file, ['Record ID', 'Employee ID', 'Gross Salary', 'Shifts Count', 'Created Date', 'Status']);

            foreach ($records as $record) {
                fputcsv($file, [
                    $record->record_id,
                    $record->user_id,
                    $record->gross_salary_input,
                    count($record->details),
                    $record->created_at->format('M j, Y H:i'),
                    $record->status
                ]);
            }

            fclose($file);
        };

        return [$filename, $callback];
    }
}
