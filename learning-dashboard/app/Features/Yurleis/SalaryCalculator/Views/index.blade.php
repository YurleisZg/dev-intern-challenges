@php
  $money = fn($v) => '$' . number_format((float)$v, 2, '.', ',');
@endphp

<x-layoutDasboard>
    <a href="/Yurleis" 
      class="px-4 py-1 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 mb-4">
      ← Back
    </a>
  <div class="flex items-start justify-between mb-6 mt-4">
    <div>
      <h1 class="text-2xl font-bold">Salary Calculator</h1>
      <p class="text-gray-600">Save and manage your calculations (fixed bonus $300).</p>
    </div>

    <a href="{{ route('yurleis.challenges.salary-calculator.create') }}"
       class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800">
      + New Calculation
    </a>
  </div>

  @if(session('status'))
    <div class="mb-4 p-3 rounded bg-green-50 text-green-700">{{ session('status') }}</div>
  @endif

  <div class="bg-white border rounded-xl overflow-hidden">
    <table class="w-full text-sm">
      <thead class="bg-gray-50 text-gray-700">
        <tr>
          <th class="text-left p-3">Date</th>
          <th class="text-left p-3">Gross</th>
          <th class="text-left p-3">Overtime</th>
          <th class="text-left p-3">Total</th>
          <th class="text-right p-3">Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($records as $r)
          <tr class="border-t">
            <td class="p-3">{{ $r->created_at->format('Y-m-d H:i') }}</td>
            <td class="p-3">{{ $money($r->gross_salary) }}</td>
            <td class="p-3">{{ $money($r->overtime_total) }}</td>
            <td class="p-3 font-semibold">{{ $money($r->grand_total) }}</td>
            <td class="p-3">
              <div class="flex justify-end gap-2">
                <a class="px-3 py-1.5 border rounded-lg hover:bg-gray-50"
                   href="{{ route('yurleis.challenges.salary-calculator.show', $r->id) }}">View</a>

                <a class="px-3 py-1.5 border rounded-lg hover:bg-gray-50"
                   href="{{ route('yurleis.challenges.salary-calculator.edit', $r->id) }}">Edit</a>

                <form method="POST" action="{{ route('yurleis.challenges.salary-calculator.destroy', $r->id) }}"
                      onsubmit="return confirm('Delete this record?')">
                  @csrf
                  @method('DELETE')
                  <button class="px-3 py-1.5 bg-red-600 text-white rounded-lg hover:bg-red-700">
                    Delete
                  </button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td class="p-4 text-gray-600" colspan="5">Don't have records yet. Create your first calculation.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</x-layoutDasboard>
