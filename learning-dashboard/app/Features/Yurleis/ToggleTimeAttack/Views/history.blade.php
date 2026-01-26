<x-layoutDasboard>
<div class="p-3">
    <div class="max-w-7xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('yurleis.challenges.toggle-time-attack.index') }}" class=" text-slate-800 border border-slate-300 px-6 py-2 rounded-md transition">
                ← Back to Game
            </a>
        </div>

        <h1 class="text-2xl font-bold text-slate-900 text-center mb-4">Game History</h1>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4 ">
            <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-6 text-center border border-gray-300">
                <div class="text-black text-sm mb-2">Total Games</div>
                <div class="text-black text-5xl font-bold">{{ $games->total() }}</div>
            </div>
            <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-6 text-center border border-gray-300">
                <div class="text-black text-sm mb-2">Completed</div>
                <div class="text-slate-400 text-5xl font-bold">
                    {{ \App\Models\Yurleis\ToggleGame::where('yurleis_user_id', Auth::guard('yurleis')->id())->where('status', 'completed')->count() }}
                </div>
            </div>
            <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-6 text-center  border border-gray-300 ">
                <div class="text-black text-sm mb-2">Failed</div>
                <div class="text-red-400 text-5xl font-bold">
                    {{ \App\Models\Yurleis\ToggleGame::where('yurleis_user_id', Auth::guard('yurleis')->id())->where('status', 'failed')->count() }}
                </div>
            </div>
        </div>

        <div class="bg-white/10 backdrop-blur-lg rounded-3xl p-8">
            @if($games->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-900 ">
                            <th class="text-left text-black font-bold py-4 px-4">ID</th>
                            <th class="text-left text-black font-bold py-4 px-4">Started</th>
                            <th class="text-left text-black font-bold py-4 px-4">Stage</th>
                            <th class="text-left text-black font-bold py-4 px-4">Status</th>
                            <th class="text-left text-black font-bold py-4 px-4">Completed</th>
                            <th class="text-left text-black font-bold py-4 px-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($games as $game)
                        <tr class="border-b border-black/10 hover:bg-black/5 transition">
                            <td class="py-4 px-4 text-black">#{{ $game->id }}</td>
                            <td class="py-4 px-4 text-black">{{ $game->created_at->format('d/m/Y H:i') }}</td>
                            <td class="py-4 px-4 text-black">Stage {{ $game->stage }}</td>
                            <td class="py-4 px-4">
                                @if($game->status === 'completed')
                                <span class=" text-black px-3 py-1 rounded-md text-sm font-semibold">✓ Completed</span>
                                @elseif($game->status === 'failed')
                                <span class="text-black px-3 py-1 rounded-md text-sm font-semibold">✗ Failed</span>
                                @elseif($game->status === 'active')
                                <span class="text-black px-3 py-1 rounded-md text-sm font-semibold">▶ Active</span>
                                @else
                                <span class="text-black px-3 py-1 rounded-md text-sm font-semibold">⊗ Abandoned</span>
                                @endif
                            </td>
                            <td class="py-4 px-4 text-black">
                                @if($game->completed_at)
                                    {{ $game->completed_at->format('d/m/Y H:i') }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="py-4 px-4">
                                <div class="flex gap-2">
                                    @if($game->status === 'active')
                                    <a href="{{ route('yurleis.challenges.toggle-time-attack.index') }}" class="text-black px-4 py-2 rounded-lg text-sm font-semibold">
                                        ▶ Resume
                                    </a>
                                    @endif
                                    
                                    @if($game->status !== 'active')
                                    <form action="{{ route('yurleis.challenges.toggle-time-attack.replay', $game->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-black px-4 py-2 rounded-lg text-sm font-semibold">
                                            🔄 Replay
                                        </button>
                                    </form>
                                    @endif

                                    @if($game->status !== 'active')
                                    <form action="{{ route('yurleis.challenges.toggle-time-attack.destroy', $game->id) }}" 
                                          method="POST" 
                                          class="inline"
                                          onsubmit="return confirm('Are you sure you want to delete this game?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-black px-4 py-2 rounded-lg text-sm font-semibold">
                                            🗑️ Delete
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $games->links() }}
            </div>
            @else
            <div class="text-center py-16">
                <p class="text-black text-2xl mb-4">No games played yet!</p>
                <p class="text-slate-700 text-lg">Start your first game to see your history here.</p>
            </div>
            @endif
        </div>
    </div>

</x-layoutDasboard>