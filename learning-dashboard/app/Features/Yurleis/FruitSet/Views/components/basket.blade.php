@php
  $route = 'yurleis.challenges.fruit-set.index';

  $theme = $theme ?? 'slate';
  $btnAddClass  = "bg-{$theme}-600 hover:bg-{$theme}-700";
  $pillClass    = "bg-{$theme}-50 border-{$theme}-200 text-{$theme}-800 hover:bg-{$theme}-100";
  $setBoxClass  = "bg-{$theme}-50 border-{$theme}-200";
  $clearBtnClass= "border-{$theme}-200 text-{$theme}-700 hover:bg-{$theme}-50";

  $renderSet = function(array $set) use ($fruits) {
    if (count($set) === 0) return '';
    $labels = array_map(fn($k) => $fruits[$k] ?? $k, $set);
    return  implode(', ', $labels);
  };
@endphp

<section class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 min-h-[420px] flex flex-col">
  <div class="flex items-start justify-between gap-3">
    <div>
      <h2 class="text-xl font-semibold flex items-center gap-2">
        Basket {{ $basket }}
      </h2>
    </div>

    <a
      href="{{ route($route, ['action' => 'clear', 'basket' => $basket, 'op' => $op ?: null]) }}"
      class="text-sm px-1 py-1 rounded-xl border {{ $clearBtnClass }}"
    >
      Clear
    </a>
  </div>

  <div class="mt-3">
    <p class="text-xs uppercase tracking-wide text-slate-500">Current Set</p>
    <div class="mt-2 rounded-xl border p-2 {{ $setBoxClass }}">
      <code class="text-xs text-slate-900">{{ $renderSet($set) }}</code>
    </div>
  </div>

  <div class="mt-4">
    <p class="text-xs uppercase tracking-wide text-slate-500">Remove</p>

    <div class="mt-1 flex flex-wrap gap-2">
      @forelse($set as $fruit)
        <a
          href="{{ route($route, ['action' => 'remove', 'basket' => $basket, 'fruit' => $fruit, 'op' => $op ?: null]) }}"
          class="px-3 py-1 rounded-xl border text-xs {{ $pillClass }}"
        >
          {{ $fruits[$fruit] ?? $fruit }} ×
        </a>
      @empty
        <span class="text-sm text-slate-400">Nothing to remove.</span>
      @endforelse
    </div>
  </div>

  <div class="mt-2 pt-2">
    <p class="text-sm font-semibold mb-2">Add to {{ $basket }}</p>

    <div class="grid grid-cols-2 gap-1">
      @foreach($fruits as $key => $label)
        <a
          href="{{ route($route, ['action' => 'add', 'basket' => $basket, 'fruit' => $key, 'op' => $op ?: null]) }}"
          class="px-4 py-1 rounded-xl text-white text-sm font-semibold text-center {{ $btnAddClass }}"
        >
          {{ $label }}
        </a>
      @endforeach
    </div>
  </div>
</section>
