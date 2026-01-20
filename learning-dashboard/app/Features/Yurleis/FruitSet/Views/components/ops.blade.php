@php
  $route = 'yurleis.challenges.fruit-set.index';

  $ops = [
    'union' => 'Union (A ∪ B)',
    'intersection' => 'Intersection (A∩B)',
    'a-b' => 'Difference (A − B)',
    'b-a' => 'Difference (B − A)',
    'xor' => 'Symmetric Diff (XOR)',
    'subset' => 'Is Subset? (A ⊆ B)',
    'jaccard' => 'Similarity % (Jaccard)',
  ];

  $fruits = $fruits ?? [];

  $renderSet = function(array $set) use ($fruits) {
    if (count($set) === 0) return '{ }';
    $labels = array_map(fn($k) => $fruits[$k] ?? $k, $set);
    return '{ ' . implode(', ', $labels) . '}';
  };
@endphp

<section class="bg-white rounded-2xl shadow-sm border border-slate-200 p-4 flex flex-col">
  <div>
    <h2 class="text-xl font-semibold">Operations</h2>
  </div>

  <div class="mt-3 grid grid-cols-2 gap-1">
    @foreach($ops as $key => $label)
      <a
        href="{{ route($route, ['op' => $key]) }}"
        class="px-2 py-2 rounded-xl border text-xs 
          {{ ($op ?? '') === $key  }}"
      >
        {{ $label }}
      </a>
    @endforeach
  </div>

  <div class="mt-auto pt-6">
    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
      <div class="flex items-start justify-between gap-3">
        <h3 class="font-semibold text-l">Result</h3>

        @if(!empty($op))
          <span class="text-xs px-2.5 py-1 rounded-full bg-white border border-slate-200 text-slate-600">
            op: {{ $op }}
          </span>
        @endif
      </div>

      <div class="mt-2">
        @if(is_array($result))
          <code class="block text-base text-slate-900">{{ $renderSet($result) }}</code>
        @elseif(is_string($result))
          <div class="text-xs font-bold text-slate-900">{{ $result }}</div>
        @else
          <div class="text-slate-500 text-sm">No operation selected.</div>
        @endif
      </div>

      <div class="mt-4">
        <p class="text-s font-semibold">Explanation</p>
        <pre class="mt-2 text-xs whitespace-pre-wrap text-slate-700 leading-relaxed">{{ $explanation}}</pre>
      </div>
    </div>
  </div>
</section>
