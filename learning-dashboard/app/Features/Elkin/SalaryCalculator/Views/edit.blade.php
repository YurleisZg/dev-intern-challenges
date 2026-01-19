<x-layoutDasboard>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Record #{{ $record->record_id }} - Salary Calculator</title>
</head>
<body>

<header class="mb-6 flex justify-between items-center px-4 py-4 border-b border-gray-200">
    <div class="flex items-center gap-3">
        <div class="h-8 w-8 rounded-lg bg-gray-900 text-white flex items-center justify-center text-sm font-semibold">✏️</div>
        <h1 class="text-xl font-semibold text-gray-900">Edit Record #{{ $record->record_id }}</h1>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('elkin.challenges.salary-calculator.show', $record->record_id) }}" class="px-4 py-2 bg-white border border-gray-300 text-gray-800 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">← Back</a>
        <a href="{{ route('elkin.dashboard') }}" class="px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition-colors">Dashboard</a>
    </div>
</header>

<div class="p-4 md:p-6">
    @if ($errors->any())
        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
            <h3 class="font-semibold text-red-800 mb-2">Errors:</h3>
            <ul class="list-disc list-inside text-red-700 text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="max-w-4xl mx-auto">
        <form method="POST" action="{{ route('elkin.challenges.salary-calculator.update', $record->record_id) }}" class="space-y-4">
            @csrf
            @method('PUT')

            <!-- Base Salary -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-200 flex items-center gap-2">
                    <span class="h-6 w-6 rounded bg-gray-900 text-white text-xs font-semibold inline-flex items-center justify-center">1</span>
                    <h2 class="text-base font-semibold text-gray-900">Base Salary</h2>
                </div>
                <div class="p-4">
                    <div>
                        <label for="gross_salary" class="block text-sm font-medium text-gray-700 mb-2">Monthly gross amount *</label>
                        <input
                            type="number"
                            name="gross_salary"
                            id="gross_salary"
                            min="0"
                            step="0.01"
                            required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-gray-900"
                            value="{{ old('gross_salary', $formData['gross_salary'] ?? '') }}"
                        >
                    </div>
                </div>
            </div>

            <!-- Overtime Records -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-200 flex justify-between items-center">
                    <div class="flex items-center gap-2">
                        <span class="h-6 w-6 rounded bg-gray-200 text-gray-800 text-xs font-semibold inline-flex items-center justify-center">2</span>
                        <h2 class="text-base font-semibold text-gray-900">Overtime records</h2>
                    </div>
                    <span class="text-xs px-2 py-1 rounded bg-gray-100 text-gray-700 border border-gray-200">{{ $overtimeRows }} records</span>
                </div>
                <div class="p-4">
                    <div class="space-y-3 mb-4">
                        @for ($i = 0; $i < $overtimeRows; $i++)
                            <div class="grid grid-cols-1 sm:grid-cols-4 gap-2 pb-3 border-b border-gray-200 last:border-b-0">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Date</label>
                                    <input
                                        type="date"
                                        name="overtime_date[]"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-gray-900 text-sm"
                                        value="{{ old('overtime_date.' . $i, $formData['overtime_date'][$i] ?? '') }}"
                                    >
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Start</label>
                                    <input
                                        type="time"
                                        name="overtime_start[]"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-gray-900 text-sm"
                                        value="{{ old('overtime_start.' . $i, $formData['overtime_start'][$i] ?? '') }}"
                                    >
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">End</label>
                                    <input
                                        type="time"
                                        name="overtime_end[]"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-gray-900 text-sm"
                                        value="{{ old('overtime_end.' . $i, $formData['overtime_end'][$i] ?? '') }}"
                                    >
                                </div>
                            </div>
                        @endfor
                    </div>

                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 px-4 py-2 bg-gray-900 text-white text-sm font-semibold rounded-lg hover:bg-gray-800 transition-colors">💾 Update Record</button>
                        <a href="{{ route('elkin.challenges.salary-calculator.show', $record->record_id) }}" class="flex-1 px-4 py-2 bg-white border border-gray-300 text-gray-800 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors text-center">Cancel</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

</body>
</html>
</x-layoutDasboard>