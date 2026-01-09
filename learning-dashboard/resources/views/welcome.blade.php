<x-layoutDasboard>
    <div class="flex flex-col items-center justify-center">
        <h1 class="text-4xl font-extrabold text-gray-900 mb-10">Welcome!</h1>

        <div class="flex space-x-6">
            <a href="{{ route('elkin.dashboard') }}"
               class="flex flex-col items-center p-10 bg-white border-2 border-transparent hover:border-gray-900 rounded-2xl shadow-lg transition-all transform hover:-translate-y-2">
                <div class="w-20 h-20 bg-gray-900 text-white rounded-full flex items-center justify-center text-3xl mb-4">
                    E
                </div>
                <span class="text-xl font-bold text-gray-800">Dashboard Elkin</span>
                <p class="text-sm text-gray-400 mt-2">View challenges</p>
            </a>

            <a href="{{ route('yurleis.dashboardYurleis') }}"
                class="flex flex-col items-center p-10 bg-white border-2 border-transparent hover:border-gray-900 rounded-2xl shadow-lg transition-all transform hover:-translate-y-2">
                <div class="w-20 h-20 bg-gray-900 text-white rounded-full flex items-center justify-center text-3xl mb-4">
                    Y
                </div>
                <span class="text-xl font-bold text-gray-800">Dashboard Yurleis</span>
                <p class="text-sm text-gray-400 mt-2">View challenges</p>
            </a>
        </div>
    </div>
</x-layoutDasboard>
