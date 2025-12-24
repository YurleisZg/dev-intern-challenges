<x-layoutDasboard>
    <div class="mb-12">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Developer Training Dashboard</h1>
        <p class="text-base text-gray-600">Track the weekly challenges and coding progress</p>
    </div>


    @foreach($stages as $stage)
        <section class="mb-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-4 px-1">{{ $stage->label }}</h2>
            <div class="bg-white rounded-custom shadow-sm border border-gray-200 overflow-hidden">
                @foreach($stage->challenges as $challenge)
                    <div class="flex justify-between items-center py-4 px-5 hover:bg-gray-50 transition-colors group">
                        <span class="text-gray-700 font-medium">
                            {{ $challenge->name }} - {{ $challenge->description }}
                        </span>
                        <a href="{{route($challenge->routeName)}}"
                           class="px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition-colors">
                            Open
                        </a>
                    </div>
                @endforeach
            </div>
        </section>
    @endforeach
</x-layoutDasboard>

