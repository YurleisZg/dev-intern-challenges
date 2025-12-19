<?php
namespace App\Services;

use App\Models\GameSession;
use App\Repositories\GameRepository;

class GameService {
    private GameRepository $repository;

    public function __construct(GameRepository $repo) {
        $this->repository = $repo;
    }

    public function processAttempt(GameSession $session, array $guess): string {
        $targetRow = $session->pattern[$session->currentLevel - 1];
        $isCorrect = true;

        for ($i = 0; $i < 5; $i++) {
            $val = isset($guess[$i]) ? 1 : 0;
            if ($val !== $targetRow[$i]) {
                $isCorrect = false;
                break;
            }
        }

        if ($isCorrect) {
            if ($session->currentLevel >= 5) {
                $session->status = 'victory';
            } else {
                $session->currentLevel++;
            }
        } else {
            $session->strikes++;
            if ($session->strikes >= 3) {
                $session->status = 'game_over';
            } elseif ($session->currentLevel > 1) {
                $session->currentLevel--; 
            }
        }

        $this->repository->update($session);
        return $session->status;
    }
}