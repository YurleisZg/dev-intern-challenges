<x-layoutDasboard>
<div class=" flex items-center justify-center p-8">
    <div class="max-w-3xl w-full">
        <div class="bg-white/10 backdrop-blur-lg rounded-3xl p-8 shadow-md">
          <h1 class="text-2xl font-bold text-black mb-3 text-center">Toggle Time Attack</h1>
                <p class="text-s text-black text-center">Test your speed and pattern matching skills!</p>

            <div class="p-6 mb-4">
                <h2 class="text-xl font-bold text-black mb-4">How to Play</h2>
                <ul class="space-y-3 text-black">
                    <li class="flex items-start">
                        <span class="text-slate-300 mr-3 text-l">▸</span>
                        <span><strong class="text-black">Stage 1:</strong> Turn ON at least 1 toggle in each of the 5 rows (20 seconds)</span>
                    </li>
                    <li class="flex items-start">
                        <span class="text-slate-300 mr-3 text-l">▸</span>
                        <span><strong class="text-black">Stage 2:</strong> Match 5 patterns against the clock (10-15s each)</span>
                    </li>
                    <li class="flex items-start">
                        <span class="text-slate-300 mr-3 text-xl">▸</span>
                        <span><strong class="text-black">Strikes:</strong> Wrong match or timeout = Strike. Go back 1 level.</span>
                    </li>
                    <li class="flex items-start">
                        <span class="text-slate-300 mr-3 text-xl">▸</span>
                        <span><strong class="text-black">Game Over:</strong> 3 strikes and you're out!</span>
                    </li>
                    <li class="flex items-start">
                        <span class="text-slate-300 mr-3 text-xl">▸</span>
                        <span><strong class="text-black">Victory:</strong> Complete all 5 levels to win!</span>
                    </li>
                </ul>
            </div>

            <form action="{{ route('yurleis.challenges.toggle-time-attack.start') }}" method="POST">
                @csrf
                <button type="submit" class="w-full bg-slate-800 text-white  text-xl py-4 px-8 rounded-xl">
                    Start New Game
                </button>
            </form>

            <div class="flex justify-center gap-6 mt-6 text-sm">
                <a href="{{ route('yurleis.challenges.toggle-time-attack.history') }}" class="text-slate-800 underline">
                    📊 Game History
                </a>
                <a href="/Yurleis" class="text-slate-800 underline">
                   <- Back to Challenges
                </a>
            </div>
        </div>
    </div>
</div>
</x-layoutDasboard>
