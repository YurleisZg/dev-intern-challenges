@php
  $money = fn($v) => '$' . number_format((float) $v, 2, '.', ',');
  $minsToHours = fn($m) => floor($m / 60) . 'h ' . ($m % 60) . 'm';
@endphp

<x-layoutDasboard>
  <div class="flex items-start justify-between mb-6">
    <div>
      <h1 class="text-2xl font-bold">Calculation Summary</h1>
      <p class="text-gray-600">Created: {{ $record->created_at->format('Y-m-d') }}</p>
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
        <div class="flex justify-between"><span>Tax
            ({{ number_format(($record->tax / $record->gross_salary) * 100, 0) }}%)</span><span>-
            {{ $money($record->tax) }}</span></div>
        <div class="flex justify-between"><span>Health (5%)</span><span>- {{ $money($record->health) }}</span></div>
        <div class="flex justify-between"><span>Bonus (300 fixed)</span><span>+ {{ $money($record->bonus) }}</span>
        </div>
        <hr>
        <div class="flex justify-between font-semibold"><span>Base
            Net</span><span>{{ $money($record->base_net) }}</span></div>
      </div>
    </div>

    <div class="bg-white border rounded-xl p-5">
      <h2 class="text-lg font-semibold mb-3">Totals</h2>
      <div class="space-y-2 text-sm">
        <div class="flex justify-between"><span> Base Net</span><span>{{ $money($record->base_net) }}</span></div>
        <hr>
        <div class="flex justify-between"><span> Overtime Total</span><span>{{ $money($record->overtime_total) }}</span>
        </div>
        <hr>
        <div class="flex justify-between text-lg font-bold"><span>Grand
            Total</span><span>{{ $money($record->grand_total) }}</span></div>
      </div>
    </div>
  </div>

  <div class="bg-white border rounded-xl p-5 mt-4">
    <h2 class="text-lg font-semibold mb-2">Overtime List</h2>
    <div class="mb-4 p-4 bg-gray-50 border-l-4 border-gray-800 rounded-r-lg">
      <ul class="text-sm text-slate-700 space-y-1">
        <li>• <strong>Hourly rate: </strong>Gross / 160</li>
        <li>• <strong>Monday–Friday:</strong> +25% of hourly rate</li>
        <li>• <strong>Saturday:</strong> overtime after 13:00 (+25% of hourly rate).</li>
        <li>• <strong>Sunday:</strong> +50%, night adds an extra 25% of hourly rate </li>
      </ul>
    </div>


    <div class="overflow-x-auto text-center">
      <table class="min-w-full text-sm">
        <thead class="bg-gray-50 text-gray-700">
          <tr>
            <th class="text-left p-2">Date</th>
            <th class="text-left p-2">Start</th>
            <th class="text-left p-2">End</th>
            <th class="text-left p-2">Overtime</th>
            <th class="text-left p-2">Night OT (Sun +25%)</th>
            <th class="text-left p-2">Sunday</th>
            <th class="text-left p-2">Hourly Rate</th>
            <th class="text-left p-2">Multiplier</th>
            <th class="text-left p-2">Overtime hourly</th>
            <th class="text-right p-2">Overtime Total</th>
          </tr>
        </thead>
        <tbody>
          @forelse($record->shifts as $s)
            <tr class="border-t">
              <td class="p-2">{{ $s->date }}</td>
              <td class="p-2">{{ substr($s->start_time, 0, 5) }}</td>
              <td class="p-2">{{ substr($s->end_time, 0, 5) }}</td>
              <td class="p-2">{{ $minsToHours($s->overtime_minutes) }}</td>
              <td class="p-2">{{ $minsToHours($s->night_overtime_minutes) }}</td>
              <td class="p-2">{{ $s->is_sunday ? 'Yes' : 'No' }}</td>
              <td class="p-2">{{ $money($s->hourly_rate) }}</td>
              <td class="p-2">
                @if($s->is_sunday)
                  1.50x / 1.75x
                @else
                  {{ number_format((float) $s->multiplier, 2) }}x
                @endif
              </td>

              <td class="p-2">
                @if($s->is_sunday)
                  Day {{ $money($s->hourly_rate * 1.50) }} / Night {{ $money($s->hourly_rate * 1.75) }}
                @else
                  {{ $money($s->hourly_rate * $s->multiplier) }}
                @endif
              </td>

              <td class="p-2 text-right font-semibold">{{ $money($s->total) }}
              </td>
            </tr>
          @empty
            <tr>
              <td class="p-3 text-gray-600" colspan="10">No overtime shifts recorded.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</x-layoutDasboard>