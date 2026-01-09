@php
  $money = fn($v) => '$' . number_format((float)$v, 2, '.', ',');
  $minsToHours = fn($m) => floor($m/60) . 'h ' . ($m%60) . 'm';
@endphp

<x-layoutDasboard>
  <div class="flex items-start justify-between mb-6">
    <div>
      <h1 class="text-2xl font-bold">Calculation Summary</h1>
      <p class="text-gray-600">Created: {{ $record->created_at->format('Y-m-d H:i') }}</p>
    </div>

    <div class="flex gap-2">
      <a class="px-4 py-2 border rounded-lg hover:bg-gray-50"
         href="{{ route('yurleis.challenges.salary-calculator.index') }}">Back</a>
      <a class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800"
         href="{{ route('yurleis.challenges.salary-calculator.edit', $record->id) }}">Edit</a>
    </div>
  </div>

  <div class="grid md:grid-cols-2 gap-4">
    <div class="bg-white border rounded-xl p-5">
      <h2 class="text-lg font-semibold mb-3">Base Salary Breakdown</h2>
      <div class="space-y-2 text-sm">
        <div class="flex justify-between"><span>Gross</span><span>{{ $money($record->gross_salary) }}</span></div>
        <div class="flex justify-between"><span>Tax</span><span>- {{ $money($record->tax) }}</span></div>
        <div class="flex justify-between"><span>Health (5%)</span><span>- {{ $money($record->health) }}</span></div>
        <div class="flex justify-between"><span>Bonus</span><span>+ {{ $money($record->bonus) }}</span></div>
        <hr>
        <div class="flex justify-between font-semibold"><span>Base Net</span><span>{{ $money($record->base_net) }}</span></div>
      </div>
    </div>

    <div class="bg-white border rounded-xl p-5">
      <h2 class="text-lg font-semibold mb-3">Totals</h2>
      <div class="space-y-2 text-sm">
        <div class="flex justify-between"><span>Total Overtime</span><span>{{ $money($record->overtime_total) }}</span></div>
        <hr>
        <div class="flex justify-between text-lg font-bold"><span>Grand Total</span><span>{{ $money($record->grand_total) }}</span></div>
      </div>
    </div>
  </div>

  <div class="bg-white border rounded-xl p-5 mt-4">
    <h2 class="text-lg font-semibold mb-3">Overtime List</h2>

    <div class="overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead class="bg-gray-50 text-gray-700">
          <tr>
            <th class="text-left p-2">Date</th>
            <th class="text-left p-2">Start</th>
            <th class="text-left p-2">End</th>
            <th class="text-left p-2">Overtime</th>
            <th class="text-left p-2">Night OT</th>
            <th class="text-left p-2">Sunday</th>
            <th class="text-left p-2">Multiplier</th>
            <th class="text-right p-2">Total</th>
          </tr>
        </thead>
        <tbody>
          @forelse($record->shifts as $s)
            <tr class="border-t">
              <td class="p-2">{{ $s->date }}</td>
              <td class="p-2">{{ substr($s->start_time,0,5) }}</td>
              <td class="p-2">{{ substr($s->end_time,0,5) }}</td>
              <td class="p-2">{{ $minsToHours($s->overtime_minutes) }}</td>
              <td class="p-2">{{ $minsToHours($s->night_overtime_minutes) }}</td>
              <td class="p-2">{{ $s->is_sunday ? 'Yes' : 'No' }}</td>
              <td class="p-2">{{ number_format((float)$s->multiplier, 2) }}x</td>
              <td class="p-2 text-right font-semibold">{{ $money($s->total) }}</td>
            </tr>
          @empty
            <tr>
              <td class="p-3 text-gray-600" colspan="8">No overtime shifts recorded.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</x-layoutDasboard>
