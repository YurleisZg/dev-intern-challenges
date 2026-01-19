<x-layoutDasboard>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Record Details - Salary Calculator</title>
</head>
<body>

<header class="mb-6 flex justify-between items-center px-4 py-4 border-b border-gray-200">
    <div class="flex items-center gap-3">
        <div class="h-8 w-8 rounded-lg bg-gray-900 text-white flex items-center justify-center text-sm font-semibold">$</div>
        <h1 class="text-xl font-semibold text-gray-900">Record #{{ $record->record_id }}</h1>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('elkin.challenges.salary-calculator.index') }}" class="px-4 py-2 bg-white border border-gray-300 text-gray-800 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">← Back</a>
        <a href="{{ route('elkin.dashboard') }}" class="px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition-colors">Dashboard</a>
    </div>
</header>

<div class="p-4 md:p-6">
    @if (session('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
            <h3 class="font-semibold text-green-800 mb-2">✓ Success</h3>
            <p class="text-green-700 text-sm">{{ session('success') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-4">
            <!-- Record Info -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-200 flex items-center gap-2">
                    <span class="h-6 w-6 rounded bg-gray-900 text-white text-xs font-semibold inline-flex items-center justify-center">i</span>
                    <h2 class="text-base font-semibold text-gray-900">Record Information</h2>
                </div>
                <div class="p-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="pb-3 border-b border-gray-200">
                            <div class="text-xs text-gray-600 mb-1">Record ID</div>
                            <div class="text-lg font-semibold text-gray-900">#{{ $record->record_id }}</div>
                        </div>
                        <div class="pb-3 border-b border-gray-200">
                            <div class="text-xs text-gray-600 mb-1">Created</div>
                            <div class="text-sm font-semibold text-gray-900">{{ $record->created_at->format('M j, Y') }}</div>
                            <div class="text-xs text-gray-500">{{ $record->created_at->format('H:i') }}</div>
                        </div>
                        <div class="pb-3 border-b border-gray-200">
                            <div class="text-xs text-gray-600 mb-1">Gross Salary</div>
                            <div class="text-lg font-bold text-gray-900">${{ number_format($record->gross_salary_input, 2) }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Overtime Shifts -->
            @if ($record->details->count() > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-4 py-3 border-b border-gray-200 flex items-center gap-2">
                        <span class="h-6 w-6 rounded bg-gray-200 text-gray-800 text-xs font-semibold inline-flex items-center justify-center">⏱</span>
                        <h3 class="text-base font-semibold text-gray-900">Overtime Shifts</h3>
                        <span class="text-xs px-2 py-1 rounded bg-gray-100 text-gray-700 border border-gray-200">{{ $record->details->count() }} records</span>
                    </div>
                    <div class="p-4">
                        <div class="space-y-3">
                            @foreach ($record->details as $detail)
                                <div class="p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                    <div class="flex justify-between items-start gap-4">
                                        <div class="flex-1">
                                            <div class="text-sm font-semibold text-gray-900">
                                                {{ \Carbon\Carbon::parse($detail->shift_date)->format('D, M j, Y') }}
                                            </div>
                                            <div class="text-xs text-gray-600 mt-1">
                                                {{ $detail->start_time }} - {{ $detail->end_time }}
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            @php
                                                $hours = \App\Features\Elkin\SalaryCalculator\Services\SalaryCalculationService::calculateHours(
                                                    $detail->start_time,
                                                    $detail->end_time
                                                );
                                            @endphp
                                            <div class="text-sm font-semibold text-gray-900">
                                                {{ number_format($hours, 2) }} hrs
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 text-center">
                    <div class="text-4xl mb-4">📭</div>
                    <p class="text-sm text-gray-600">No overtime shifts recorded for this calculation.</p>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1 space-y-4">
            <!-- Statistics -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden sticky top-6">
                <div class="px-4 py-3 border-b border-gray-200 flex items-center gap-2">
                    <span class="h-6 w-6 rounded bg-gray-200 text-gray-800 text-xs font-semibold inline-flex items-center justify-center">Σ</span>
                    <h3 class="text-base font-semibold text-gray-900">Statistics</h3>
                </div>
                <div class="p-4 space-y-3">
                    <div class="pb-3 border-b border-gray-200">
                        <div class="text-xs text-gray-600 mb-1">Total Shifts</div>
                        <div class="text-2xl font-bold text-gray-900">{{ $record->details->count() }}</div>
                    </div>
                    <div class="pb-3 border-b border-gray-200">
                        <div class="text-xs text-gray-600 mb-1">Total Hours</div>
                        <div class="text-2xl font-bold text-gray-900">
                            @php
                                $totalHours = 0;
                                foreach ($record->details as $detail) {
                                    $totalHours += \App\Features\Elkin\SalaryCalculator\Services\SalaryCalculationService::calculateHours(
                                        $detail->start_time,
                                        $detail->end_time
                                    );
                                }
                            @endphp
                            {{ number_format($totalHours, 2) }}
                        </div>
                    </div>
                    @if ($record->details->count() > 0)
                        <div>
                            <div class="text-xs text-gray-600 mb-1">Avg Hours/Shift</div>
                            <div class="text-2xl font-bold text-gray-900">
                                {{ number_format($totalHours / $record->details->count(), 2) }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-200 flex items-center gap-2">
                    <span class="h-6 w-6 rounded bg-gray-200 text-gray-800 text-xs font-semibold inline-flex items-center justify-center">⚙</span>
                    <h3 class="text-base font-semibold text-gray-900">Actions</h3>
                </div>
                <div class="p-4 space-y-2">
                    <a href="{{ route('elkin.challenges.salary-calculator.edit', $record->record_id) }}" class="block w-full px-4 py-2 bg-white border border-gray-300 text-gray-800 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors text-center">
                        ✏️ Edit Record
                    </a>
                    <form method="POST" action="{{ route('elkin.challenges.salary-calculator.delete', $record->record_id) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full px-4 py-2 bg-white border border-gray-300 text-red-600 text-sm font-medium rounded-lg hover:bg-red-50 transition-colors">
                            🗑️ Delete Record
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
</x-layoutDasboard>
