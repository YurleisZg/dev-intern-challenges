@php
  $isEdit = !is_null($record);
  $action = $isEdit
    ? route('yurleis.challenges.salary-calculator.update', $record->id)
    : route('yurleis.challenges.salary-calculator.store');

  $back = route('yurleis.challenges.salary-calculator.index');
@endphp

<x-layoutDasboard>
  <div class="flex items-start justify-between mb-4">
    <div>
      <h1 class="text-2xl font-bold">{{ $isEdit ? 'Edit Calculation' : 'New Calculation' }}</h1>
      <p class="text-gray-600"> You can save even if you don't have overtime hours.</p>
    </div>

    <a href="{{ $back }}" class="px-4 py-1 border rounded-lg hover:bg-gray-50">Back</a>
  </div>

  @if($errors->any())
    <div class="mb-2 p-3 rounded bg-red-50 text-red-700">
      <ul class="list-disc ml-5">
        @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
      </ul>
    </div>
  @endif

  <form method="POST" action="{{ $action }}" class="space-y-2">
    @csrf
    @if($isEdit) @method('PUT') @endif

    <div class="bg-white border rounded-xl p-4">
      <label class="block text-l font-medium font-semibold text-gray-700">Gross Monthly Salary</label>
      <input type="number" step="1" name="gross_salary" class="mt-2 w-full border rounded-lg p-2"
        value="{{ old('gross_salary', $record->gross_salary ?? '') }}" placeholder="Ej: 2500" required>
      <div class="mt-3 text-sm text-slate-700">
        <ul class="mt-1 space-y-1 px-3">
          <p class="font-medium text-slate-900">Base salary rules</p>
          <li>• <strong>Tax:</strong> Gross Salary &lt; 1000 (0%), 1000–2000 (10%), &gt; 2000 (20%)</li>
          <li>• <strong>Health:</strong> −5% of gross</li>
          <li>• <strong>Bonus:</strong> 300 fixed</li>
        </ul>
      </div>

    </div>

    <div class="bg-white border rounded-xl p-4">
      <div class="flex items-center justify-between mb-2">
        <h2 class="text-l font-semibold">Overtime Shifts (optional)</h2>
        <button type="button" id="addShift" class="px-3 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800">
          + Add shift
        </button>
      </div>

      <div class="mb-4 p-4 bg-blue-50 border-l-4 border-blue-400 rounded-r-lg">
        <h3 class="font-medium text-blue-900 mb-2">Overtime Rules:</h3>
        <ul class="text-sm text-slate-700 space-y-1">
          <li>• <strong>Hourly rate: </strong>Gross Monthly Salary / 160</li>
          <li>• <strong>Monday–Friday:</strong> overtime 18:00 – 06:00 (+25% of hourly rate)</li>
          <li>• <strong>Saturday:</strong> normal 06:00 – 13:00, overtime otherwise (+25% of hourly rate)</li>
          <li>• <strong>Sunday:</strong> is always overtime (+50%), night adds an extra 25% of hourly rate </li>
        </ul>
      </div>

      <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
          <thead class="bg-gray-50 text-gray-700">
            <tr>
              <th class="text-left p-2">Date</th>
              <th class="text-left p-2">Start</th>
              <th class="text-left p-2">End</th>
              <th class="text-right p-2">Action</th>
            </tr>
          </thead>
          <tbody id="shiftsBody">
            @php
              $old = old('overtime');
              $rows = is_array($old) ? $old : ($shifts ?? []);
            @endphp

            @forelse($rows as $i => $row)
              <tr class="border-t shift-row">
                <td class="p-2">
                  <input type="date" name="overtime[{{ $i }}][date]" class="w-full border rounded-lg p-2"
                    value="{{ $row['date'] ?? '' }}">
                </td>
                <td class="p-2">
                  <input type="time" name="overtime[{{ $i }}][start_time]" class="w-full border rounded-lg p-2"
                    value="{{ $row['start_time'] ?? '' }}">
                </td>
                <td class="p-2">
                  <input type="time" name="overtime[{{ $i }}][end_time]" class="w-full border rounded-lg p-2"
                    value="{{ $row['end_time'] ?? '' }}">
                </td>
                <td class="p-2 text-right">
                  <button type="button" class="removeShift px-3 py-2 border rounded-lg hover:bg-gray-50">
                    Remove
                  </button>
                </td>
              </tr>
            @empty
            @endforelse
          </tbody>
        </table>
      </div>

      <p class="text-xs text-gray-500 mt-3">
        Add your overtime shifts here. You can add multiple shifts and remove them as needed.
      </p>
    </div>

    <div class="flex justify-end gap-2">
      <a href="{{ $back }}" class="px-4 py-2 border rounded-lg hover:bg-gray-50">Cancel</a>
      <button class="px-4 py-2 bg-slate-800 text-white rounded-lg hover:bg-slate-700">
        {{ $isEdit ? 'Update' : 'Calculate and Save' }}
      </button>
    </div>
  </form>

  <template id="shiftTemplate">
    <tr class="border-t shift-row">
      <td class="p-2">
        <input type="date" class="w-full border rounded-lg p-2" data-name="date">
      </td>
      <td class="p-2">
        <input type="time" class="w-full border rounded-lg p-2" data-name="start_time">
      </td>
      <td class="p-2">
        <input type="time" class="w-full border rounded-lg p-2" data-name="end_time">
      </td>
      <td class="p-2 text-right">
        <button type="button" class="removeShift px-3 py-2 border rounded-lg hover:bg-gray-50">
          Remove
        </button>
      </td>
    </tr>
  </template>

  <script>
    (function () {
      const body = document.getElementById('shiftsBody');
      const addBtn = document.getElementById('addShift');
      const tpl = document.getElementById('shiftTemplate');

      function reindex() {
        const rows = body.querySelectorAll('.shift-row');
        rows.forEach((row, idx) => {
          row.querySelectorAll('input').forEach(inp => {
            const key = inp.getAttribute('data-name') || (inp.name.match(/\[(date|start_time|end_time)\]/)?.[1]);
            if (!key) return;
            inp.name = `overtime[${idx}][${key}]`;
          });
        });
      }

      function bindRemove(row) {
        row.querySelector('.removeShift')?.addEventListener('click', () => {
          row.remove();
          reindex();
        });
      }

      body.querySelectorAll('.shift-row').forEach(bindRemove);

      addBtn.addEventListener('click', () => {
        const node = tpl.content.cloneNode(true);
        const row = node.querySelector('tr');
        row.querySelectorAll('input').forEach(inp => {
          const key = inp.getAttribute('data-name');
          inp.removeAttribute('data-name');
          inp.name = `overtime[${body.querySelectorAll('.shift-row').length}][${key}]`;
        });
        body.appendChild(row);
        bindRemove(row);
        reindex();
      });
    })();
  </script>
</x-layoutDasboard>