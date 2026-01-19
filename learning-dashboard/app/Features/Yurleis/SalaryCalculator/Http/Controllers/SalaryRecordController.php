<?php

namespace App\Features\Yurleis\SalaryCalculator\Http\Controllers;

use App\Features\Yurleis\SalaryCalculator\Services\SalaryCalculatorService;
use App\Models\Yurleis\SalaryRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SalaryRecordController
{
    public function index()
    {
        $userId = Auth::guard('yurleis')->id();

        $records = SalaryRecord::query()
            ->where('yurleis_user_id', $userId)
            ->latest()
            ->get();

                return view('yurleis-salary-calculator::index', compact('records'));
    }

    public function create()
    {
        $record = null;
        $shifts = [];
        return view('yurleis-salary-calculator::form', compact('record', 'shifts'));
    }

    public function store(Request $request, SalaryCalculatorService $calc)
    {
        $userId = Auth::guard('yurleis')->id();

        $data = $request->validate([
            'gross_salary' => ['required', 'numeric', 'min:0'],

            'overtime' => ['nullable', 'array'],
            'overtime.*.date' => ['required_with:overtime.*.start_time,overtime.*.end_time', 'date'],
            'overtime.*.start_time' => ['required_with:overtime.*.date,overtime.*.end_time', 'date_format:H:i'],
            'overtime.*.end_time' => ['required_with:overtime.*.date,overtime.*.start_time', 'date_format:H:i'],
        ]);

        $gross = (float) $data['gross_salary'];
        $shiftsInput = $this->normalizeShifts($data['overtime'] ?? []);

        $result = $calc->calculate($gross, $shiftsInput);

        return DB::transaction(function () use ($userId, $result) {
            $record = SalaryRecord::create([
                'yurleis_user_id' => $userId,
                'gross_salary' => $result['gross_salary'],

                'tax' => $result['tax'],
                'health' => $result['health'],
                'bonus' => $result['bonus'], 
                'base_net' => $result['base_net'],

                'overtime_total' => $result['overtime_total'],
                'grand_total' => $result['grand_total'],
            ]);

            foreach ($result['shift_rows'] as $row) {
                $record->shifts()->create([
                    'date' => $row['date'],
                    'start_time' => $row['start_time'],
                    'end_time' => $row['end_time'],

                    'overtime_minutes' => $row['overtime_minutes'],
                    'night_overtime_minutes' => $row['night_overtime_minutes'],
                    'is_sunday' => $row['is_sunday'],

                    'hourly_rate' => $row['hourly_rate'],
                    'multiplier' => $row['multiplier'],
                    'total' => $row['total'],
                ]);
            }

            return redirect()->route('yurleis.challenges.salary-calculator.show', $record->id);
        });
    }

    public function show(int $record)
    {
        $userId = Auth::guard('yurleis')->id();

        $record = SalaryRecord::query()
            ->where('yurleis_user_id', $userId)
            ->with('shifts')
            ->findOrFail($record);

        return view('yurleis-salary-calculator::show', compact('record'));
    }

    public function edit(int $record)
    {
        $userId = Auth::guard('yurleis')->id();

        $record = SalaryRecord::query()
            ->where('yurleis_user_id', $userId)
            ->with('shifts')
            ->findOrFail($record);

        $shifts = $record->shifts->map(fn ($s) => [
            'date' => $s->date,
            'start_time' => substr($s->start_time, 0, 5),
            'end_time' => substr($s->end_time, 0, 5),
        ])->values()->all();

        return view('yurleis-salary-calculator::form', compact('record', 'shifts'));
    }

    public function update(int $record, Request $request, SalaryCalculatorService $calc)
    {
        $userId = Auth::guard('yurleis')->id();

        $record = SalaryRecord::query()
            ->where('yurleis_user_id', $userId)
            ->with('shifts')
            ->findOrFail($record);

        $data = $request->validate([
            'gross_salary' => ['required', 'numeric', 'min:0'],

            'overtime' => ['nullable', 'array'],
            'overtime.*.date' => ['required_with:overtime.*.start_time,overtime.*.end_time', 'date'],
            'overtime.*.start_time' => ['required_with:overtime.*.date,overtime.*.end_time', 'date_format:H:i'],
            'overtime.*.end_time' => ['required_with:overtime.*.date,overtime.*.start_time', 'date_format:H:i'],
        ]);

        $gross = (float) $data['gross_salary'];
        $shiftsInput = $this->normalizeShifts($data['overtime'] ?? []);
        $result = $calc->calculate($gross, $shiftsInput);

        return DB::transaction(function () use ($record, $result) {
            $record->update([
                'gross_salary' => $result['gross_salary'],
                'tax' => $result['tax'],
                'health' => $result['health'],
                'bonus' => $result['bonus'], // 300 fijo
                'base_net' => $result['base_net'],
                'overtime_total' => $result['overtime_total'],
                'grand_total' => $result['grand_total'],
            ]);

            // replace shifts (simple and safe)
            $record->shifts()->delete();

            foreach ($result['shift_rows'] as $row) {
                $record->shifts()->create([
                    'date' => $row['date'],
                    'start_time' => $row['start_time'],
                    'end_time' => $row['end_time'],
                    'overtime_minutes' => $row['overtime_minutes'],
                    'night_overtime_minutes' => $row['night_overtime_minutes'],
                    'is_sunday' => $row['is_sunday'],
                    'hourly_rate' => $row['hourly_rate'],
                    'multiplier' => $row['multiplier'],
                    'total' => $row['total'],
                ]);
            }

            return redirect()->route('yurleis.challenges.salary-calculator.show', $record->id);
        });
    }

    public function destroy(int $record)
    {
        $userId = Auth::guard('yurleis')->id();

        $record = SalaryRecord::query()
            ->where('yurleis_user_id', $userId)
            ->findOrFail($record);

        $record->delete();

        return redirect()->route('yurleis.challenges.salary-calculator.index')
            ->with('status', 'Record deleted successfully.');
    }

    private function normalizeShifts(array $overtime): array
    {
        // remove empty rows
        $clean = [];
        foreach ($overtime as $row) {
            $date = trim((string)($row['date'] ?? ''));
            $start = trim((string)($row['start_time'] ?? ''));
            $end = trim((string)($row['end_time'] ?? ''));

            if ($date === '' || $start === '' || $end === '') continue;

            $clean[] = [
                'date' => $date,
                'start_time' => $start,
                'end_time' => $end,
            ];
        }
        return $clean;
    }
}
