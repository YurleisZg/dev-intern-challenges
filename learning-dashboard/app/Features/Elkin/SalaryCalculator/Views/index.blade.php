<x-layoutDasboard>
<div class="py-4">
    <header class="mb-6 flex justify-between items-center">
        <div class="flex items-center gap-3">
            <div class="h-10 w-10 rounded-lg bg-gray-900 text-white flex items-center justify-center text-lg font-bold">$</div>
            <div>
                <h1 class="text-xl font-semibold text-gray-900">Salary Calculator</h1>
                <p class="text-sm text-gray-500">Calculate salaries with overtime bonuses</p>
            </div>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('elkin.dashboard') }}"
               class="px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50">
                ← Dashboard
            </a>
            <form method="POST" action="{{ route('elkin.challenges.salary-calculator.reset') }}">
                @csrf
                <button class="px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800">
                    Logout
                </button>
            </form>
        </div>
    </header>

    {{-- FILA SUPERIOR: HISTORIAL Y FORMULARIOS --}}
    <div class="flex flex-col lg:flex-row gap-4 mb-4">
        
        {{-- COLUMNA IZQUIERDA: HISTORIAL --}}
        <aside class="w-full lg:w-3/12">
            <div class="bg-white rounded-xl border shadow-sm flex flex-col">
                <div class="px-3 py-2 border-b bg-gray-50 font-semibold text-sm">📋 Saved Records</div>
                <div class="flex-1 overflow-y-auto p-2 space-y-1 max-h-[350px]">
                    @forelse ($records as $record)
                        <div class="border rounded p-2 text-xs">
                            <div class="font-bold text-gray-900">#{{ $record->record_id }}</div>
                            <div class="font-semibold text-gray-700">${{ number_format($record->gross_salary_input, 2) }}</div>
                            <div class="text-gray-500 text-[11px] mb-1">
                                {{ count($record->details) }} shifts · {{ $record->updated_at->format('M j') }}
                            </div>
                            <div class="flex gap-0.5">
                                <a href="{{ route('elkin.challenges.salary-calculator.show', $record->record_id) }}"
                                   class="flex-1 text-center border rounded py-0.5 text-[10px] hover:bg-gray-50">View</a>
                                <form method="POST"
                                      action="{{ route('elkin.challenges.salary-calculator.delete', $record->record_id) }}"
                                      onsubmit="return confirm('Delete this record?')"
                                      class="flex-1">
                                    @csrf @method('DELETE')
                                    <button class="w-full text-[10px] border rounded py-0.5 text-red-600 hover:bg-red-50">Delete</button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-gray-500 text-center py-4">No saved records</p>
                    @endforelse
                </div>
            </div>
        </aside>

        {{-- COLUMNA DERECHA: FORMULARIOS --}}
        <div class="w-full lg:w-9/12 space-y-4">
            <form method="POST" 
                  action="{{ isset($editingRecord) ? route('elkin.challenges.salary-calculator.edit', $editingRecord->record_id) : route('elkin.challenges.salary-calculator.calculate') }}" 
                  class="space-y-4">
                @csrf

                {{-- BASE SALARY --}}
                <div class="bg-white rounded-xl border shadow-sm text-sm">
                    <div class="px-4 py-3 border-b bg-gray-50 font-semibold flex items-center gap-2">
                        <span class="h-6 w-6 bg-gray-900 text-white rounded text-xs flex items-center justify-center">1</span>
                        Base Salary
                    </div>
                    <div class="p-4">
                        <label class="block mb-2">Monthly gross amount <span class="text-red-600">*</span></label>
                        <input type="number" name="gross_salary" required step="0.01"
                               value="{{ old('gross_salary', $formData['gross_salary'] ?? '') }}"
                               class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-gray-900">
                    </div>
                </div>

                {{-- OVERTIME --}}
                <div class="bg-white rounded-xl border shadow-sm">
                    <div class="px-4 py-3 border-b bg-gray-50 flex justify-between items-center">
                        <div class="flex items-center gap-2 font-semibold">
                            <span class="h-6 w-6 bg-gray-200 rounded text-xs flex items-center justify-center">2</span>
                            Overtime Records
                        </div>
                        <span class="text-xs border px-2 py-1 rounded">{{ $overtimeRows }} records</span>
                    </div>

                    <div class="p-4 space-y-3">
                        @for ($i = 0; $i < $overtimeRows; $i++)
                            <div class="grid grid-cols-12 gap-3 border-b pb-3 last:border-b-0">
                                <div class="col-span-4 lg:col-span-3">
                                    <label class="text-[10px] uppercase font-bold text-gray-400">Date</label>
                                    <input type="date" name="overtime_date[]" value="{{ old('overtime_date.' . $i, $formData['overtime_date'][$i] ?? '') }}" class="w-full px-2 py-1 border rounded text-sm">
                                </div>
                                <div class="col-span-3 lg:col-span-4">
                                    <label class="text-[10px] uppercase font-bold text-gray-400">Start</label>
                                    <input type="time" name="overtime_start[]" value="{{ old('overtime_start.' . $i, $formData['overtime_start'][$i] ?? '') }}" class="w-full px-2 py-1 border rounded text-sm">
                                </div>
                                <div class="col-span-3 lg:col-span-4">
                                    <label class="text-[10px] uppercase font-bold text-gray-400">End</label>
                                    <input type="time" name="overtime_end[]" value="{{ old('overtime_end.' . $i, $formData['overtime_end'][$i] ?? '') }}" class="w-full px-2 py-1 border rounded text-sm">
                                </div>
                                <div class="col-span-2 lg:col-span-1 flex items-end">
                                    @if ($overtimeRows > 1)
                                        <button type="submit" name="action" value="remove-row-{{ $i }}" class="w-full border rounded text-red-600 hover:bg-red-50 p-1">×</button>
                                    @endif
                                </div>
                            </div>
                        @endfor
                        <button type="submit" name="action" value="add-row" class="px-4 py-2 border rounded hover:bg-gray-50 text-sm font-medium">+ Add Overtime</button>
                    </div>
                </div>

                <button type="submit" name="action" value="calculate" class="w-full px-6 py-3 bg-gray-900 text-white font-semibold rounded-lg hover:bg-gray-800 transition-colors">
                    Calculate Salary
                </button>
            </form>
        </div>
    </div> {{-- AQUÍ CERRAMOS LA FILA SUPERIOR --}}

    {{-- ✅ SECCIÓN INFERIOR: RESUMEN (FULL WIDTH) --}}
    @if($result)
        <div class="space-y-4">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-200 bg-gray-50 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="h-6 w-6 rounded bg-gray-900 text-white text-xs font-semibold flex items-center justify-center">$</span>
                        <h2 class="text-base font-semibold text-gray-900">Calculation Summary</h2>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs text-gray-500 uppercase font-bold">Total Net</span>
                        <div class="bg-gray-900 text-white px-3 py-1 rounded-lg text-lg font-bold">
                            ${{ number_format($result['grand_total'], 2) }}
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        {{-- Deductions --}}
                        <div class="space-y-1 text-sm border-b md:border-b-0 md:border-r border-gray-100 pb-4 md:pb-0 md:pr-6">
                            <h3 class="text-[11px] font-bold text-gray-400 uppercase mb-3">Deductions & Bonuses</h3>
                            <div class="flex justify-between mb-1">
                                <span class="text-gray-600">Gross salary</span>
                                <span class="font-semibold">${{ number_format($result['gross_salary'], 2) }}</span>
                            </div>
                            <div class="flex justify-between text-red-600 mb-1">
                                <span>Tax ({{ $result['gross_salary'] <= 2000 ? '10%' : '20%' }})</span>
                                <span class="font-semibold">-${{ number_format($result['tax'], 2) }}</span>
                            </div>
                            <div class="flex justify-between text-red-600 mb-1">
                                <span>Health (5%)</span>
                                <span class="font-semibold">-${{ number_format($result['health'], 2) }}</span>
                            </div>
                            <div class="flex justify-between text-green-600">
                                <span>Bonus (Random)</span>
                                <span class="font-semibold">+${{ number_format($result['bonus'], 2) }}</span>
                            </div>
                        </div>

                        {{-- Hourly Rate --}}
                        <div class="flex flex-col items-center justify-center text-center">
                            <span class="text-[11px] font-bold text-gray-400 uppercase mb-2">Hourly Rate</span>
                            <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 w-full max-w-[200px]">
                                <span class="text-blue-700 font-bold text-2xl block">${{ number_format($result['hourly_rate'], 2) }}/hr</span>
                                <p class="text-[10px] text-blue-500 mt-1">Based on 160h standard month</p>
                            </div>
                        </div>

                        {{-- Base Net --}}
                        <div class="flex flex-col items-center justify-center text-center">
                            <span class="text-[11px] font-bold text-gray-400 uppercase mb-2">Base Net Salary</span>
                            <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 w-full max-w-[200px]">
                                <span class="text-gray-900 font-bold text-2xl block">${{ number_format($result['base_salary'], 2) }}</span>
                                <p class="text-[10px] text-gray-500 mt-1">(Before overtime)</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Overtime Breakdown (Opcional si hay datos) --}}
            @if (!empty($result['overtime_data']))
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-4 py-3 border-b border-gray-200 bg-amber-50 flex items-center gap-2">
                        <span class="h-6 w-6 rounded bg-amber-200 text-amber-900 text-xs font-semibold flex items-center justify-center">⚡</span>
                        <h2 class="text-base font-semibold text-gray-900">Overtime Details</h2>
                    </div>
                    <div class="p-4 flex justify-between items-center text-sm">
                        <div class="flex gap-8">
                            <div><span class="text-gray-500">Total Hours:</span> <span class="font-bold ml-1">{{ number_format(array_sum(array_column($result['overtime_data'], 'hours')), 2) }}</span></div>
                            <div><span class="text-gray-500">Total Pay:</span> <span class="font-bold text-green-600 ml-1">${{ number_format($result['total_overtime'], 2) }}</span></div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    @endif
</div>
</x-layoutDasboard>