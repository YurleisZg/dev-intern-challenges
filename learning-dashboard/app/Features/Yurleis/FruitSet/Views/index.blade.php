<x-layoutDasboard>
  <a href="/Yurleis" class="px-4 py-2 border rounded-lg hover:bg-gray-50">
    <- Back
  </a>
  <div>
    <div class="flex items-center justify-between gap-3 mb-7 mt-3">
      <div>
        <h1 class="text-2xl sm:text-3xl font-bold">Fruit Set Logic</h1>
        <p class="text-slate-600 mt-1 text-sm">
          Fill Basket A and B. Then execute set operations and understand the result.
        </p>
      </div>

      <a
        href="{{ route('yurleis.challenges.fruit-set.index', ['reset' => 1]) }}"
        class="px-4 py-1 rounded-MD bg-red-700 text-white text-sm"
      >
        Clear All
      </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-2">
      @include('yurleis-fruit-set::components.basket', [
        'basket' => 'A',
        'set' => $a,
        'fruits' => $fruits,
        'op' => $op,
        'theme' => 'teal',
      ])

      @include('yurleis-fruit-set::components.basket', [
        'basket' => 'B',
        'set' => $b,
        'fruits' => $fruits,
        'op' => $op,
        'theme' => 'slate',
      ])

      @include('yurleis-fruit-set::components.ops', [
        'op' => $op,
        'result' => $result,
        'explanation' => $explanation,
        'fruits' => $fruits,
      ])
    </div>
  </div>
</x-layoutDasboard>
