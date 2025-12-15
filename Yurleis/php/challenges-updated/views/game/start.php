<div class="game-container">

    <div class="card" style="text-align: center; padding: 2rem;">
        <?php if ($activeSession): ?>
            <h2 style="color: #003a70; margin-bottom: 1rem;">You have a game in progress!</h2>
            <p class="description">You left off at <strong>Level <?php echo $activeSession->currentLevel; ?></strong>.</p>
            <div class="button-row" style="justify-content: center; margin-top: 1.5rem;">
                <a href="index.php?action=pattern_game&step=stage2" class="btn btn-primary">Continue Game</a>
                <a href="index.php?action=pattern_game&step=stage1" class="btn btn-red" onclick="return confirm('This will delete your current progress. Do you want to start from scratch?')">New Game</a>
            </div>
        <?php else: ?>
            <p class="description">
                Create a 5-row pattern, memorize it and beat it level by level.<br>
                <strong>You have 3 strikes before losing.</strong>
            </p>
            <a class="btn btn-primary" href="index.php?action=pattern_game&step=stage1" style="display: inline-block; margin-top: 1rem;">Start Game</a>
        <?php endif; ?>
    </div>

    <section class="card results-card" style="margin-top: 2rem;">
        <h2 class="card-title">Your Game History</h2>
        <table class="table-container">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Level</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($history)): ?>
                    <tr><td colspan="4" class="placeholder-text">You haven't recorded any games yet.</td></tr>
                <?php else: ?>
                    <?php foreach ($history as $record): ?>
                        <tr>
                            <td><?php echo date('m/d/Y H:i', strtotime($record->playedAt ?? $record->created_at)); ?></td>
                            <td>Level <?php echo $record->currentLevel; ?>/5</td>
                            <td>
                                <span class="badge <?php echo $record->status === 'victory' ? 'status-win' : ($record->status === 'game_over' ? 'status-lose' : 'status-progress'); ?>">
                                    <?php echo str_replace('_', ' ', $record->status); ?>
                                </span>
                            </td>
                            <td>
                                <a href="index.php?action=pattern_game&delete_id=<?php echo $record->id; ?>" 
                                   class="btn-link-red" 
                                   onclick="return confirm('Delete this record?')">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </section>
</div>