<x-layoutDasboard>
<div class="py-4">
    {{-- HEADER (Consistente) --}}
    <header class="mb-6 flex justify-between items-center">
        <div class="flex items-center gap-3">
            <div class="h-10 w-10 rounded-lg bg-gray-900 text-white flex items-center justify-center text-lg font-bold">$</div>
            <div>
                <h1 class="text-xl font-semibold text-gray-900">Salary Calculator</h1>
                <p class="text-sm text-gray-500">Professional payroll & overtime breakdown</p>
            </div>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('elkin.dashboard') }}" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50">← Dashboard</a>
        </div>
    </header>

    {{-- SECCIÓN DE ENTRADA (Mantenida según tu estructura) --}}
    <div class="flex flex-col lg:flex-row gap-4 mb-6">
        <aside class="w-full lg:w-3/12">
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm flex flex-col h-full">
                <div class="px-4 py-3 border-b border-gray-100 font-bold text-xs uppercase tracking-wider text-gray-500">Saved Records</div>
                <div class="flex-1 overflow-y-auto p-2 space-y-2 max-h-[300px]">
                    @forelse ($records as $record)
                        <div class="group border border-gray-100 rounded-lg p-3 hover:border-gray-300 transition-all">
                            <div class="flex justify-between items-start mb-1">
                                <span class="text-[10px] font-bold text-gray-400">#{{ $record->record_id }}</span>
                                <span class="text-[10px] text-gray-400">{{ $record->updated_at->format('M j') }}</span>
                            </div>
                            <div class="text-sm font-bold text-gray-900">${{ number_format($record->gross_salary_input, 2) }}</div>
                        </div>
                    @empty
                        <p class="text-xs text-gray-400 text-center py-6">No records</p>
                    @endforelse
                </div>
            </div>
        </aside>

        <div class="w-full lg:w-9/12 space-y-4">
            <form method="POST" action="{{ route('elkin.challenges.salary-calculator.calculate') }}" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
                        <label class="block text-[10px] font-bold uppercase text-gray-400 mb-2">Monthly Gross Salary</label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-gray-400">$</span>
                            <input type="number" name="gross_salary" required step="0.01" value="{{ old('gross_salary', $formData['gross_salary'] ?? '') }}" class="w-full pl-7 pr-3 py-2 bg-gray-50 border-transparent rounded-lg focus:bg-white focus:ring-2 focus:ring-gray-900 text-sm font-semibold">
                        </div>
                    </div>

                    <div class="md:col-span-2 bg-white rounded-xl border border-gray-200 p-4 shadow-sm flex justify-between items-center">
                        <div>
                            <span class="block text-[10px] font-bold uppercase text-gray-400">Shifts to calculate</span>
                            <span class="text-xl font-bold text-gray-900">{{ $overtimeRows }} <span class="text-sm font-normal text-gray-400">entries</span></span>
                        </div>
                        <div class="flex gap-2">
                            <button type="submit" name="action" value="add-row" class="px-4 py-2 border border-gray-200 rounded-lg text-xs font-bold hover:bg-gray-50">+ Add Row</button>
                            <button type="submit" name="action" value="calculate" class="px-6 py-2 bg-gray-900 text-white rounded-lg text-xs font-bold hover:bg-gray-800">Calculate</button>
                        </div>
                    </div>
                </div>

                {{-- TABLA DE ENTRADA --}}
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 border-b border-gray-100 text-[10px] font-bold uppercase text-gray-400">
                            <tr>
                                <th class="px-4 py-2">Date</th>
                                <th class="px-4 py-2">Start</th>
                                <th class="px-4 py-2">End</th>
                                <th class="px-4 py-2 w-10"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @for ($i = 0; $i < $overtimeRows; $i++)
                                <tr>
                                    <td class="px-4 py-2"><input type="date" name="overtime_date[]" value="{{ old('overtime_date.' . $i, $formData['overtime_date'][$i] ?? '') }}" class="w-full bg-transparent border-none text-sm p-0 focus:ring-0"></td>
                                    <td class="px-4 py-2"><input type="time" name="overtime_start[]" value="{{ old('overtime_start.' . $i, $formData['overtime_start'][$i] ?? '') }}" class="w-full bg-transparent border-none text-sm p-0 focus:ring-0"></td>
                                    <td class="px-4 py-2"><input type="time" name="overtime_end[]" value="{{ old('overtime_end.' . $i, $formData['overtime_end'][$i] ?? '') }}" class="w-full bg-transparent border-none text-sm p-0 focus:ring-0"></td>
                                    <td class="px-4 py-2 text-right">
                                        @if ($overtimeRows > 1)
                                            <button type="submit" name="action" value="remove-row-{{ $i }}" class="text-gray-300 hover:text-red-500">×</button>
                                        @endif
                                    </td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>
            </form>
        </div>
    </div>

    {{-- ✅ RESULTADOS --}}
    @if($result)
        <div class="space-y-4 animate-in fade-in slide-in-from-bottom-2">
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                {{-- KPIS --}}
                <div class="grid grid-cols-1 md:grid-cols-4 divide-y md:divide-y-0 md:divide-x divide-gray-100 border-b border-gray-100">
                    
                    {{-- EXPLICACIÓN DE BASE & DEDUCTIONS --}}
                    <div class="p-6 col-span-1 md:col-span-1">
                        <span class="block text-[10px] font-bold text-gray-400 uppercase mb-3">Base & Deductions</span>
                        <div class="space-y-2">
                            <div class="flex justify-between text-xs">
                                <span class="text-gray-500 italic">Gross Salary</span>
                                <span class="font-bold text-gray-900">${{ number_format($result['gross_salary'], 2) }}</span>
                            </div>
                            <div class="flex justify-between text-[10px]">
                                <span class="text-red-500">Health & Pension (4%)</span>
                                <span class="font-bold text-red-500">-${{ number_format($result['health'], 2) }}</span>
                            </div>
                            <div class="flex justify-between text-[10px]">
                                <span class="text-red-500">Income Tax (8%)</span>
                                <span class="font-bold text-red-500">-${{ number_format($result['tax'], 2) }}</span>
                            </div>
                            <div class="flex justify-between text-[10px]">
                                <span class="text-green-600 font-medium">Bonus</span>
                                <span class="font-bold text-green-600">+${{ number_format($result['bonus'], 2) }}</span>
                            </div>
                            <div class="pt-2 border-t border-dashed flex justify-between text-xs font-black text-gray-900">
                                <span>Net Base</span>
                                <span>${{ number_format($result['base_salary'], 2) }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="p-6 flex flex-col justify-center text-center">
                        <span class="text-[10px] font-bold text-gray-400 uppercase mb-1">Regular Rate</span>
                        <div class="text-xl font-bold text-gray-900">${{ number_format($result['hourly_rate'], 2) }}</div>
                        <span class="text-[9px] text-gray-400">Value per regular hour</span>
                    </div>

                    <div class="p-6 flex flex-col justify-center text-center bg-blue-50/30">
                        <span class="text-[10px] font-bold text-gray-400 uppercase mb-1">Overtime Total</span>
                        <div class="text-2xl font-black text-blue-600">+${{ number_format($result['total_overtime'], 2) }}</div>
                        <span class="text-[9px] text-blue-400 font-bold">{{ count($result['overtime_data']) }} shifts added</span>
                    </div>

                    <div class="p-6 bg-gray-900 text-white flex flex-col justify-center">
                        <span class="text-[10px] font-bold text-gray-400 uppercase mb-1">Final Net Total</span>
                        <div class="text-3xl font-black">${{ number_format($result['grand_total'], 2) }}</div>
                        <span class="text-[9px] text-gray-400">Base + Overtime Pay</span>
                    </div>
                </div>

                {{-- TABLA DETALLE (CON SOLUCIÓN DE BREAKDOWN) --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-xs">
                        <thead class="bg-gray-50/50 text-[9px] font-bold uppercase text-gray-400">
                            <tr>
                                <th class="px-6 py-3 text-left">Shift Date</th>
                                <th class="px-6 py-3 text-center">Hours</th>
                                <th class="px-6 py-3 text-right">Shift Pay</th>
                                <th class="px-6 py-3 text-left">Hourly Breakdown</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach ($result['overtime_data'] as $shift)
                                <tr class="hover:bg-gray-50/80 transition-colors">
                                    <td class="px-6 py-3 whitespace-nowrap">
                                        <div class="font-bold text-gray-900">{{ \Carbon\Carbon::parse($shift['date'])->format('D, M j') }}</div>
                                        <div class="text-[10px] text-gray-400">{{ $shift['start'] }} - {{ $shift['end'] }}</div>
                                    </td>
                                    <td class="px-6 py-3 text-center font-bold text-gray-700">{{ number_format($shift['total_hours'], 1) }}h</td>
                                    <td class="px-6 py-3 text-right font-bold text-gray-900">${{ number_format($shift['shift_total'], 2) }}</td>
                                    <td class="px-6 py-3">
                                        <div class="flex flex-wrap gap-1">
                                            {{-- LÓGICA DE AGRUPACIÓN PARA EVITAR SCROLL --}}
                                            @php
                                                $segments = collect($shift['segments']);
                                                $multiplierGroups = $segments->groupBy(fn($s) => (string)$s['multiplier']);
                                            @endphp

                                            @foreach($multiplierGroups as $mult => $items)
                                                <div class="inline-flex items-center rounded-md border border-gray-200 bg-white px-2 py-1 shadow-sm">
                                                    <span class="text-[10px] font-black text-gray-900">{{ $mult }}x</span>
                                                    <span class="mx-1 text-gray-300">|</span>
                                                    <span class="text-[9px] text-gray-600 font-medium">{{ $items->count() }}h</span>
                                                </div>
                                            @endforeach
                                            
                                            {{-- INDICADOR VISUAL SI ES UN TURNO MIXTO --}}
                                            @if($multiplierGroups->count() > 1)
                                                <span class="flex items-center px-2 py-1 rounded-md bg-amber-50 text-amber-600 text-[8px] font-bold uppercase tracking-tighter">Mixed Rate</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
</div>
</x-layoutDasboard>