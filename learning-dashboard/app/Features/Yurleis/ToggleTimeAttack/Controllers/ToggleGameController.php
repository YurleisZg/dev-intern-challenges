<?php

namespace App\Features\Yurleis\ToggleTimeAttack\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Yurleis\ToggleGame;

class ToggleGameController
{
    public function index()
    {
        if (!Auth::guard('yurleis')->check()) {
            return redirect()->route('yurleis.challenges.auth.login');
        }

        $user = Auth::guard('yurleis')->user();
        
        // Get active game or show start page
        $game = ToggleGame::where('yurleis_user_id', $user->id)
            ->where('status', 'active')
            ->first();

        if (!$game) {
            return view('yurleis-toggle-time-attack::start');
        }

        // Route to appropriate stage
        if ($game->stage === 1) {
            return $this->showStage1($game);
        } else {
            return $this->showStage2($game);
        }
    }

    public function startGame()
    {
        if (!Auth::guard('yurleis')->check()) {
            return redirect()->route('yurleis.challenges.auth.login');
        }

        $user = Auth::guard('yurleis')->user();

        // End any active games
        ToggleGame::where('yurleis_user_id', $user->id)
            ->where('status', 'active')
            ->update(['status' => 'abandoned']);

        // Create new game
        $game = ToggleGame::create([
            'yurleis_user_id' => $user->id,
            'stage' => 1,
            'status' => 'active',
            'stage1_data' => json_encode([
                'rows' => [
                    [false, false, false, false, false],
                    [false, false, false, false, false],
                    [false, false, false, false, false],
                    [false, false, false, false, false],
                    [false, false, false, false, false],
                ],
                'started_at' => now()->timestamp,
            ]),
            'stage2_data' => json_encode([
                'current_level' => 0,
                'strikes' => 0,
            ]),
        ]);

        return redirect()->route('yurleis.challenges.toggle-time-attack.index');
    }

    public function updateStage1(Request $request)
    {
        if (!Auth::guard('yurleis')->check()) {
            return response()->json(['success' => false], 401);
        }

        $user = Auth::guard('yurleis')->user();
        $game = ToggleGame::where('yurleis_user_id', $user->id)
            ->where('status', 'active')
            ->where('stage', 1)
            ->first();

        if (!$game) {
            return response()->json(['success' => false, 'message' => 'Game not found'], 404);
        }

        $row = $request->input('row');
        $col = $request->input('col');
        $value = $request->boolean('value');

        $data = json_decode($game->stage1_data, true);
        
        if (isset($data['rows'][$row][$col])) {
            $data['rows'][$row][$col] = $value;
            $game->stage1_data = json_encode($data);
            $game->save();
        }

        return response()->json(['success' => true]);
    }

    public function submitStage1(Request $request)
    {
        if (!Auth::guard('yurleis')->check()) {
            return response()->json(['success' => false], 401);
        }

        $user = Auth::guard('yurleis')->user();
        $game = ToggleGame::where('yurleis_user_id', $user->id)
            ->where('status', 'active')
            ->where('stage', 1)
            ->first();

        if (!$game) {
            return response()->json(['success' => false, 'message' => 'Game not found'], 404);
        }

        $data = json_decode($game->stage1_data, true);
        $startedAt = $data['started_at'] ?? now()->timestamp;
        $elapsed = now()->timestamp - $startedAt;

        // Check time limit (20 seconds)
        if ($elapsed > 20) {
            $game->status = 'failed';
            $game->save();
            return response()->json(['success' => false, 'message' => 'Time expired!']);
        }

        // Validate: each row must have at least one toggle ON
        foreach ($data['rows'] as $index => $row) {
            if (!in_array(true, $row, true)) {
                $game->status = 'failed';
                $game->save();
                return response()->json([
                    'success' => false, 
                    'message' => 'Row ' . ($index + 1) . ' has no toggles turned on!'
                ]);
            }
        }

        // Advance to Stage 2
        $game->stage = 2;
        $game->stage2_data = json_encode([
            'current_level' => 1,
            'strikes' => 0,
            'level_started_at' => now()->timestamp,
            'level_duration' => rand(10, 15),
            'input_state' => [false, false, false, false, false],
        ]);
        $game->save();

        return response()->json(['success' => true, 'redirect' => route('yurleis.challenges.toggle-time-attack.index')]);
    }

    public function updateStage2(Request $request)
    {
        if (!Auth::guard('yurleis')->check()) {
            return response()->json(['success' => false], 401);
        }

        $user = Auth::guard('yurleis')->user();
        $game = ToggleGame::where('yurleis_user_id', $user->id)
            ->where('status', 'active')
            ->where('stage', 2)
            ->first();

        if (!$game) {
            return response()->json(['success' => false, 'message' => 'Game not found'], 404);
        }

        $col = $request->input('col');
        $value = $request->boolean('value');

        $data = json_decode($game->stage2_data, true);
        
        if (isset($data['input_state'][$col])) {
            $data['input_state'][$col] = $value;
            $game->stage2_data = json_encode($data);
            $game->save();
        }

        return response()->json(['success' => true]);
    }

    public function submitStage2(Request $request)
    {
        if (!Auth::guard('yurleis')->check()) {
            return response()->json(['success' => false], 401);
        }

        $user = Auth::guard('yurleis')->user();
        $game = ToggleGame::where('yurleis_user_id', $user->id)
            ->where('status', 'active')
            ->where('stage', 2)
            ->first();

        if (!$game) {
            return response()->json(['success' => false, 'message' => 'Game not found'], 404);
        }

        $stage1Data = json_decode($game->stage1_data, true);
        $stage2Data = json_decode($game->stage2_data, true);

        $currentLevel = $stage2Data['current_level'];
        $targetRow = $stage1Data['rows'][$currentLevel - 1];
        $inputState = $stage2Data['input_state'];

        $startedAt = $stage2Data['level_started_at'] ?? now()->timestamp;
        $duration = $stage2Data['level_duration'] ?? 15;
        $elapsed = now()->timestamp - $startedAt;

        // Check time
        $timeExpired = $elapsed > $duration;

        // Check match
        $isMatch = $targetRow === $inputState;

        if ($timeExpired || !$isMatch) {
            // Add strike
            $stage2Data['strikes']++;
            
            // Check for game over (3 strikes)
            if ($stage2Data['strikes'] >= 3) {
                $game->status = 'failed';
                $game->save();
                return response()->json([
                    'success' => false, 
                    'game_over' => true,
                    'message' => 'Game Over! 3 strikes reached.'
                ]);
            }

            // Go back one level
            $stage2Data['current_level'] = max(1, $currentLevel - 1);
            $stage2Data['level_started_at'] = now()->timestamp;
            $stage2Data['level_duration'] = rand(10, 15);
            $stage2Data['input_state'] = [false, false, false, false, false];
            
            $game->stage2_data = json_encode($stage2Data);
            $game->save();

            $message = $timeExpired ? 'Time expired!' : 'Pattern does not match!';
            return response()->json([
                'success' => false,
                'message' => $message . ' Strike ' . $stage2Data['strikes'] . '/3. Going back to Level ' . $stage2Data['current_level']
            ]);
        }

        // Success - advance to next level
        if ($currentLevel >= 5) {
            // Victory!
            $game->status = 'completed';
            $game->completed_at = now();
            $game->save();
            
            return response()->json([
                'success' => true,
                'victory' => true,
                'message' => 'Congratulations! You completed all 5 levels!'
            ]);
        }

        // Next level
        $stage2Data['current_level']++;
        $stage2Data['level_started_at'] = now()->timestamp;
        $stage2Data['level_duration'] = rand(10, 15);
        $stage2Data['input_state'] = [false, false, false, false, false];
        
        $game->stage2_data = json_encode($stage2Data);
        $game->save();

        return response()->json([
            'success' => true,
            'message' => 'Level ' . ($currentLevel) . ' complete! Moving to Level ' . $stage2Data['current_level']
        ]);
    }

    public function history()
    {
        if (!Auth::guard('yurleis')->check()) {
            return redirect()->route('yurleis.challenges.auth.login');
        }

        $user = Auth::guard('yurleis')->user();
        
        $games = ToggleGame::where('yurleis_user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('yurleis-toggle-time-attack::history', compact('games'));
    }

    public function replay($id)
    {
        if (!Auth::guard('yurleis')->check()) {
            return redirect()->route('yurleis.challenges.auth.login');
        }

        $user = Auth::guard('yurleis')->user();
        
        $oldGame = ToggleGame::where('yurleis_user_id', $user->id)
            ->where('id', $id)
            ->firstOrFail();

        // End any active games
        ToggleGame::where('yurleis_user_id', $user->id)
            ->where('status', 'active')
            ->update(['status' => 'abandoned']);

        $oldStage1Data = json_decode($oldGame->stage1_data, true);
        
        $game = ToggleGame::create([
            'yurleis_user_id' => $user->id,
            'stage' => 1,
            'status' => 'active',
            'stage1_data' => json_encode([
                'rows' => $oldStage1Data['rows'] ?? [
                    [false, false, false, false, false],
                    [false, false, false, false, false],
                    [false, false, false, false, false],
                    [false, false, false, false, false],
                    [false, false, false, false, false],
                ],
                'started_at' => now()->timestamp,
            ]),
            'stage2_data' => json_encode([
                'current_level' => 0,
                'strikes' => 0,
            ]),
        ]);

        return redirect()->route('yurleis.challenges.toggle-time-attack.index');
    }

    public function edit(Request $request, $id)
    {
        if (!Auth::guard('yurleis')->check()) {
            return redirect()->route('yurleis.challenges.auth.login');
        }

        $user = Auth::guard('yurleis')->user();
        
        $game = ToggleGame::where('yurleis_user_id', $user->id)
            ->where('id', $id)
            ->firstOrFail();

        $validated = $request->validate([
            'stage' => 'required|in:1,2',
            'status' => 'required|in:active,completed,failed,abandoned',
        ]);

        $game->update($validated);

        return redirect()->route('yurleis.challenges.toggle-time-attack.history')
            ->with('success', 'Game updated successfully!');
    }

    public function destroy($id)
    {
        if (!Auth::guard('yurleis')->check()) {
            return redirect()->route('yurleis.challenges.auth.login');
        }

        $user = Auth::guard('yurleis')->user();
        
        $game = ToggleGame::where('yurleis_user_id', $user->id)
            ->where('id', $id)
            ->firstOrFail();

        $game->delete();

        return redirect()->route('yurleis.challenges.toggle-time-attack.history')
            ->with('success', 'Game deleted successfully');
    }

    private function showStage1($game)
    {
        $data = json_decode($game->stage1_data, true);
        $startedAt = $data['started_at'] ?? now()->timestamp;
        $timeLeft = max(0, 20 - (now()->timestamp - $startedAt));

        if ($timeLeft <= 0 && $game->status === 'active') {
            $game->status = 'failed';
            $game->save();

            // opcional: llevar al start del juego
            return redirect()->route('yurleis.challenges.toggle-time-attack.index');
        }

        return view('yurleis-toggle-time-attack::stage1', [
            'game' => $game,
            'rows' => $data['rows'],
            'timeLeft' => $timeLeft,
        ]);
    }


    private function showStage2($game)
    {
        $stage1Data = json_decode($game->stage1_data, true);
        $stage2Data = json_decode($game->stage2_data, true);

        $startedAt = $stage2Data['level_started_at'] ?? now()->timestamp;
        $duration  = $stage2Data['level_duration'] ?? 15;
        $timeLeft  = max(0, $duration - (now()->timestamp - $startedAt));

        // ✅ Si ya expiró el nivel, forzar submit para que aplique strike / game over / etc.
        if ($timeLeft <= 0 && $game->status === 'active') {
            // Aquí llamamos la lógica existente que ya maneja strikes y game_over
            $this->submitStage2(request());
            return redirect()->route('yurleis.challenges.toggle-time-attack.index');
        }

        $currentLevel = $stage2Data['current_level'];
        $targetRow = $stage1Data['rows'][$currentLevel - 1];
        $inputState = $stage2Data['input_state'];
        $strikes = $stage2Data['strikes'];

        return view('yurleis-toggle-time-attack::stage2', [
            'game' => $game,
            'currentLevel' => $currentLevel,
            'targetRow' => $targetRow,
            'inputState' => $inputState,
            'strikes' => $strikes,
            'timeLeft' => $timeLeft,
        ]);
    }

    public function abandon()
    {
        if (!Auth::guard('yurleis')->check()) {
            return response()->json(['success' => false], 401);
        }

        $user = Auth::guard('yurleis')->user();

        ToggleGame::where('yurleis_user_id', $user->id)
            ->where('status', 'active')
            ->update(['status' => 'abandoned']);

        return response()->json(['success' => true]);
    }

}
