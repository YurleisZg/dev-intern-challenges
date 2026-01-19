<x-layoutDasboard>
<div class="py-4">
    <header class="mb-6 flex justify-between items-center">
        <div class="flex items-center gap-2">
            <div class="h-8 w-8 rounded-lg bg-gray-900 text-white flex items-center justify-center text-sm font-semibold">🍎</div>
            <div>
                <h1 class="text-xl font-semibold text-gray-900">Fruit Set Logic</h1>
                <p class="text-sm text-gray-500">Explore set operations with visual basket management</p>
            </div>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('elkin.challenges.fruit-set-logic.reset', ['basket' => 'all']) }}" 
               class="px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition-colors" 
               onclick="return confirm('Reset both baskets?')">Reset All</a>
        </div>
    </header>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
        <!-- Basket A -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-4 py-3 border-b border-gray-200 flex justify-between items-center">
                <div class="flex items-center gap-2">
                    <span class="h-6 w-6 rounded bg-gray-900 text-white text-xs font-semibold inline-flex items-center justify-center">A</span>
                    <h2 class="text-base font-semibold text-gray-900">Basket A</h2>
                </div>
                <a href="{{ route('elkin.challenges.fruit-set-logic.reset', ['basket' => 'A']) }}" 
                   class="text-xs bg-white border border-gray-300 text-gray-700 px-2 py-1 rounded-lg hover:bg-gray-50 transition-colors">reset</a>
            </div>
            <div class="p-4">
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-2 mb-3 text-sm">
                    <strong class="text-gray-800">Contents:</strong> 
                    @if(empty($basketA))
                        <em class="text-gray-500">Empty</em>
                    @else
                        <span class="text-gray-700">{{ implode(", ", $basketA) }}</span>
                    @endif
                </div>

                <div class="grid grid-cols-2 gap-2">
                    @foreach($fruits as $fruit)
                        <a href="{{ route('elkin.challenges.fruit-set-logic.add', ['fruit' => $fruit, 'basket' => 'A']) }}" 
                           class="text-center px-3 py-1.5 bg-white border border-gray-300 text-gray-800 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                            + {{ $fruit }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Basket B -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-4 py-3 border-b border-gray-200 flex justify-between items-center">
                <div class="flex items-center gap-2">
                    <span class="h-6 w-6 rounded bg-gray-200 text-gray-800 text-xs font-semibold inline-flex items-center justify-center">B</span>
                    <h2 class="text-base font-semibold text-gray-900">Basket B</h2>
                </div>
                <a href="{{ route('elkin.challenges.fruit-set-logic.reset', ['basket' => 'B']) }}" 
                   class="text-xs bg-white border border-gray-300 text-gray-700 px-2 py-1 rounded-lg hover:bg-gray-50 transition-colors">reset</a>
            </div>
            <div class="p-4">
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-2 mb-3 text-sm">
                    <strong class="text-gray-800">Contents:</strong> 
                    @if(empty($basketB))
                        <em class="text-gray-500">Empty</em>
                    @else
                        <span class="text-gray-700">{{ implode(", ", $basketB) }}</span>
                    @endif
                </div>

                <div class="grid grid-cols-2 gap-2">
                    @foreach($fruits as $fruit)
                        <a href="{{ route('elkin.challenges.fruit-set-logic.add', ['fruit' => $fruit, 'basket' => 'B']) }}" 
                           class="text-center px-3 py-1.5 bg-white border border-gray-300 text-gray-800 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                            + {{ $fruit }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Operations Panel -->
    <div class="mb-4">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-4 py-3 border-b border-gray-200 flex items-center gap-2">
                <span class="h-6 w-6 rounded bg-gray-200 text-gray-800 text-xs font-semibold inline-flex items-center justify-center">∪</span>
                <h2 class="text-base font-semibold text-gray-900">Set Operations</h2>
            </div>
            <div class="p-4">
                <p class="text-gray-600 text-xs mb-3">Click an operation to compute:</p>
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-2">
                    <a href="{{ route('elkin.challenges.fruit-set-logic.operation', ['op' => 'union']) }}" class="px-3 py-1.5 bg-white border border-gray-300 text-gray-800 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors text-center">Union (A ∪ B)</a>
                    <a href="{{ route('elkin.challenges.fruit-set-logic.operation', ['op' => 'intersect']) }}" class="px-3 py-1.5 bg-white border border-gray-300 text-gray-800 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors text-center">Intersection (A ∩ B)</a>
                    <a href="{{ route('elkin.challenges.fruit-set-logic.operation', ['op' => 'diffAB']) }}" class="px-3 py-1.5 bg-white border border-gray-300 text-gray-800 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors text-center">A - B</a>
                    <a href="{{ route('elkin.challenges.fruit-set-logic.operation', ['op' => 'diffBA']) }}" class="px-3 py-1.5 bg-white border border-gray-300 text-gray-800 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors text-center">B - A</a>
                    <a href="{{ route('elkin.challenges.fruit-set-logic.operation', ['op' => 'xor']) }}" class="px-3 py-1.5 bg-white border border-gray-300 text-gray-800 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors text-center">Symmetric Diff</a>
                    <a href="{{ route('elkin.challenges.fruit-set-logic.operation', ['op' => 'subset']) }}" class="px-3 py-1.5 bg-white border border-gray-300 text-gray-800 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors text-center">Is A ⊆ B?</a>
                    <a href="{{ route('elkin.challenges.fruit-set-logic.operation', ['op' => 'jaccard']) }}" class="px-3 py-1.5 bg-white border border-gray-300 text-gray-800 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors text-center">Similarity %</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Result -->
    @if(isset($result) && $result !== null)
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-4 py-3 border-b border-gray-200 flex items-center gap-2">
            <span class="h-6 w-6 rounded bg-gray-200 text-gray-800 text-xs font-semibold inline-flex items-center justify-center">✓</span>
            <h2 class="text-base font-semibold text-gray-900">Result</h2>
        </div>
        <div class="p-4">
            <p class="mb-3">
                <strong class="text-gray-800">Operation:</strong> <span class="text-gray-700">{{ $explanation }}</span>
            </p>
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-3 mb-3">
                <strong class="text-gray-800">Output:</strong>
                @if(is_array($result))
                    <code class="text-sm bg-white border border-gray-300 px-2 py-1 rounded text-gray-900">[{{ implode(", ", $result) }}]</code>
                @else
                    <code class="text-sm bg-white border border-gray-300 px-2 py-1 rounded text-gray-900">{{ $result }}</code>
                @endif
            </div>
            <small class="text-gray-600 text-xs">
                <strong>Basket A:</strong> {{ empty($basketA) ? "Empty" : implode(", ", $basketA) }} | 
                <strong>Basket B:</strong> {{ empty($basketB) ? "Empty" : implode(", ", $basketB) }}
            </small>
        </div>
    </div>
    @endif

</div>
</x-layoutDasboard>
