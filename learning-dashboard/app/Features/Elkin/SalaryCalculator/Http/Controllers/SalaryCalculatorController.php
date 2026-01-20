<?php

namespace App\Features\Elkin\SalaryCalculator\Http\Controllers;

use App\Features\Elkin\SalaryCalculator\Services\SalaryCalculationService;
use App\Features\Elkin\SalaryCalculator\Models\SalaryRecord;
use App\Features\Elkin\SalaryCalculator\Models\SalaryRecordDetail;
use App\Features\Elkin\SalaryCalculator\Events\SalaryCalculated;
use App\Features\Elkin\SalaryCalculator\Events\SalaryRecordDeleted;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SalaryCalculatorController
{
    public function index(Request $request)
    {
        $overtimeRows = $request->session()->get('salary.overtime_rows', 1);
        $formData = $this->getFormData($request);
        $result = null;
        $records = [];

        // Get user's salary records if authenticated
        if (auth('elkin')->check()) {
            $records = SalaryRecord::where('user_id', auth('elkin')->id())
                ->with('details')
                ->latest()
                ->limit(10)
                ->get();
        }

        return view('salary-calculator::index', [
            'overtimeRows' => $overtimeRows,
            'formData' => $formData,
            'result' => $result,
            'records' => $records,
            'validationErrors' => [],
        ]);
    }

    public function calculate(Request $request)
    {
        // Check which action was performed
        $action = $request->input('action');
        $editingRecordId = $request->input('editing_record_id');
        $editingRecord = null;

        // If editing, load the record
        if ($editingRecordId) {
            $editingRecord = SalaryRecord::with('details')->findOrFail($editingRecordId);
            if (auth('elkin')->id() !== $editingRecord->user_id) {
                abort(403, 'Unauthorized');
            }
        }

        // Handle remove-row action
        if (strpos($action, 'remove-row-') === 0) {
            $overtimeRows = $request->session()->get('salary.overtime_rows', 1);

            if ($overtimeRows > 1) {
                $overtimeRows--;
                $request->session()->put('salary.overtime_rows', $overtimeRows);
            }

            $formData = $this->getFormData($request);
            $records = [];
            if (auth('elkin')->check()) {
                $records = SalaryRecord::where('user_id', auth('elkin')->id())
                    ->with('details')
                    ->latest()
                    ->limit(10)
                    ->get();
            }

            $response = [
                'overtimeRows' => $overtimeRows,
                'formData' => $formData,
                'result' => null,
                'records' => $records,
                'validationErrors' => [],
            ];

            if ($editingRecord) {
                $response['editingRecord'] = $editingRecord;
            }

            return view('salary-calculator::index', $response);
        }

        if ($action === 'add-row') {
            $overtimeRows = $request->session()->get('salary.overtime_rows', 1);
            $overtimeRows++;
            $request->session()->put('salary.overtime_rows', $overtimeRows);
            
            $overtimeRows = $request->session()->get('salary.overtime_rows', 1);
            $formData = $this->getFormData($request);
            
            $response = [
                'overtimeRows' => $overtimeRows,
                'formData' => $formData,
                'result' => null,
                'records' => auth('elkin')->check() ? SalaryRecord::where('user_id', auth('elkin')->id())->with('details')->latest()->limit(10)->get() : [],
                'validationErrors' => [],
            ];

            if ($editingRecord) {
                $response['editingRecord'] = $editingRecord;
            }

            return view('salary-calculator::index', $response);
        }

        $overtimeRows = $request->session()->get('salary.overtime_rows', 1);
        $formData = $this->getFormData($request);

        $grossSalary = (float) ($request->input('gross_salary') ?? 0);

        if ($grossSalary <= 0) {
            $response = [
                'overtimeRows' => $overtimeRows,
                'formData' => $formData,
                'result' => null,
                'records' => auth('elkin')->check() ? SalaryRecord::where('user_id', auth('elkin')->id())->with('details')->latest()->limit(10)->get() : [],
                'errors' => ['gross_salary' => 'Gross salary must be greater than 0'],
                'validationErrors' => ['Gross salary must be greater than 0'],
            ];

            if ($editingRecord) {
                $response['editingRecord'] = $editingRecord;
            }

            return view('salary-calculator::index', $response);
        }

        // Process overtime data
        $overtimeDates = $request->input('overtime_date', []);
        $overtimeTimes = [];
        $validationErrors = [];

        if (is_array($overtimeDates)) {
            foreach ($overtimeDates as $index => $date) {
                $overtimeTimes[$index] = [
                    'start' => $request->input("overtime_start.$index"),
                    'end' => $request->input("overtime_end.$index"),
                ];

                $startTime = $overtimeTimes[$index]['start'];
                $endTime = $overtimeTimes[$index]['end'];

                if (!empty($startTime) && !empty($endTime) && strtotime($endTime) < strtotime($startTime)) {
                    $validationErrors[] = 'Row ' . ($index + 1) . ': end time must be after start time.';
                }
            }
        }

        if (!empty($validationErrors)) {
            $records = auth('elkin')->check() ? SalaryRecord::where('user_id', auth('elkin')->id())->with('details')->latest()->limit(10)->get() : [];

            $response = [
                'overtimeRows' => $overtimeRows,
                'formData' => $formData,
                'result' => null,
                'records' => $records,
                'validationErrors' => $validationErrors,
            ];

            if ($editingRecord) {
                $response['editingRecord'] = $editingRecord;
            }

            return view('salary-calculator::index', $response);
        }

        $result = SalaryCalculationService::calculateSalary($grossSalary, $overtimeDates, $overtimeTimes);

        // If editing, update the record instead of creating a new one
        if ($editingRecord && auth('elkin')->check()) {
            $editingRecord->update([
                'gross_salary_input' => $grossSalary,
                'status' => 'completed',
            ]);

            // Delete old details
            $editingRecord->details()->delete();

            // Save new overtime details
            if (is_array($overtimeDates) && !empty($overtimeDates)) {
                foreach ($overtimeDates as $index => $date) {
                    if (!empty($date)) {
                        SalaryRecordDetail::create([
                            'record_id' => $editingRecord->record_id,
                            'shift_date' => $date,
                            'start_time' => $overtimeTimes[$index]['start'] ?? null,
                            'end_time' => $overtimeTimes[$index]['end'] ?? null,
                        ]);
                    }
                }
            }

            // Dispatch event
            SalaryCalculated::dispatch($editingRecord, $result);
        } elseif (auth('elkin')->check()) {
            // Save the record if user is authenticated
            $this->saveRecord($grossSalary, $overtimeDates, $overtimeTimes, $result);
        }

        $records = [];
        if (auth('elkin')->check()) {
            $records = SalaryRecord::where('user_id', auth('elkin')->id())
                ->with('details')
                ->latest()
                ->limit(10)
                ->get();
        }

        $response = [
            'overtimeRows' => $overtimeRows,
            'formData' => $formData,
            'result' => $result,
            'records' => $records,
            'message' => $editingRecord ? 'Record updated successfully!' : 'Record saved successfully!',
            'validationErrors' => [],
        ];

        if ($editingRecord) {
            $response['editingRecord'] = $editingRecord;
        }

        return view('salary-calculator::index', $response);
    }

    public function addRow(Request $request)
    {
        $overtimeRows = $request->session()->get('salary.overtime_rows', 1);
        $overtimeRows++;
        $request->session()->put('salary.overtime_rows', $overtimeRows);

        return redirect()->route('elkin.challenges.salary-calculator.index')->withInput();
    }

    public function removeRow(Request $request, $index)
    {
        $overtimeRows = $request->session()->get('salary.overtime_rows', 1);

        if ($overtimeRows > 1) {
            $overtimeRows--;
            $request->session()->put('salary.overtime_rows', $overtimeRows);
        }

        $formData = $this->getFormData($request);
        $records = [];
        if (auth('elkin')->check()) {
            $records = SalaryRecord::where('user_id', auth('elkin')->id())
                ->with('details')
                ->latest()
                ->limit(10)
                ->get();
        }

        return view('salary-calculator::index', [
            'overtimeRows' => $overtimeRows,
            'formData' => $formData,
            'result' => null,
            'records' => $records,
            'validationErrors' => [],
        ]);
    }

    public function reset(Request $request)
    {
        $request->session()->forget(['salary.overtime_rows', 'salary.form_data']);
        $request->session()->put('salary.overtime_rows', 1);

        return redirect()->route('elkin.challenges.salary-calculator.index');
    }

    public function show($recordId)
    {
        $record = SalaryRecord::findOrFail($recordId);

        if (auth('elkin')->id() !== $record->user_id) {
            abort(403, 'Unauthorized');
        }

        return view('salary-calculator::show', ['record' => $record]);
    }

    public function delete($recordId)
    {
        $record = SalaryRecord::findOrFail($recordId);

        if (auth('elkin')->id() !== $record->user_id) {
            abort(403, 'Unauthorized');
        }

        // Dispatch event before deleting
        SalaryRecordDeleted::dispatch($record);

        $record->delete();

        return redirect()->route('elkin.challenges.salary-calculator.index')
            ->with('success', 'Record deleted successfully');
    }

    public function edit(Request $request, $recordId)
    {
        $record = SalaryRecord::with('details')->findOrFail($recordId);

        if (auth('elkin')->id() !== $record->user_id) {
            abort(403, 'Unauthorized');
        }

        $overtimeRows = count($record->details) > 0 ? count($record->details) : 1;
        $request->session()->put('salary.overtime_rows', $overtimeRows);

        $formData = [
            'gross_salary' => $record->gross_salary_input,
            'overtime_date' => $record->details->pluck('shift_date')->toArray(),
            'overtime_start' => $record->details->pluck('start_time')->toArray(),
            'overtime_end' => $record->details->pluck('end_time')->toArray(),
        ];

        // Get user's salary records
        $records = SalaryRecord::where('user_id', auth('elkin')->id())
            ->with('details')
            ->latest()
            ->limit(10)
            ->get();

        return view('salary-calculator::index', [
            'editingRecord' => $record,
            'overtimeRows' => $overtimeRows,
            'formData' => $formData,
            'result' => null,
            'records' => $records,
            'validationErrors' => [],
        ]);
    }

    private function saveRecord($grossSalary, $overtimeDates, $overtimeTimes, $result)
    {
        $record = SalaryRecord::create([
            'user_id' => auth('elkin')->id(),
            'gross_salary_input' => $grossSalary,
            'status' => 'completed',
        ]);

        // Save overtime details
        if (is_array($overtimeDates) && !empty($overtimeDates)) {
            foreach ($overtimeDates as $index => $date) {
                if (!empty($date)) {
                    SalaryRecordDetail::create([
                        'record_id' => $record->record_id,
                        'shift_date' => $date,
                        'start_time' => $overtimeTimes[$index]['start'] ?? null,
                        'end_time' => $overtimeTimes[$index]['end'] ?? null,
                    ]);
                }
            }
        }

        // Dispatch event
        SalaryCalculated::dispatch($record, $result);

        return $record;
    }

    private function getFormData(Request $request)
    {
        $overtimeRows = $request->session()->get('salary.overtime_rows', 1);

        return [
            'gross_salary' => $request->input('gross_salary', ''),
            'overtime_date' => $request->input('overtime_date', array_fill(0, $overtimeRows, '')),
            'overtime_start' => $request->input('overtime_start', array_fill(0, $overtimeRows, '')),
            'overtime_end' => $request->input('overtime_end', array_fill(0, $overtimeRows, '')),
        ];
    }

    /**
     * Get user's salary statistics
     */
    public function getStatistics()
    {
        if (!auth('elkin')->check()) {
            return [];
        }

        $records = SalaryRecord::where('user_id', auth('elkin')->id())
            ->with('details')
            ->get();

        $totalRecords = $records->count();
        $totalGrossSalary = $records->sum('gross_salary_input');
        $totalShifts = $records->sum(fn($r) => $r->details->count());

        return [
            'total_records' => $totalRecords,
            'total_gross_salary' => $totalGrossSalary,
            'total_shifts' => $totalShifts,
            'average_gross_salary' => $totalRecords > 0 ? $totalGrossSalary / $totalRecords : 0,
            'average_shifts_per_record' => $totalRecords > 0 ? $totalShifts / $totalRecords : 0,
        ];
    }
}
