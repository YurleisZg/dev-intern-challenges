<?php
namespace App\Controllers;

use App\Repositories\GameRepository;
use App\Services\GameService;
use App\Models\GameSession;

class GameController {
    private GameRepository $repository;
    private GameService $service;
    private int $userId;

    public function __construct() {
        $this->repository = new GameRepository();
        $this->service = new GameService($this->repository);
        $this->userId = $_SESSION['user_id'] ?? 0;
    }

  public function index(): string {
    if (isset($_GET['delete_id'])) {
        $this->repository->delete((int)$_GET['delete_id'], $this->userId);
        header('Location: index.php?action=pattern_game');
        exit;
    }

    $activeSession = $this->repository->findActiveSession($this->userId);
    
    $history = $this->repository->getHistory($this->userId); 
    
    ob_start();
    require __DIR__ . '/../../views/game/start.php';
    return ob_get_clean();
}

    public function handleStage1(): string {

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        unset($_SESSION['deadline_stage1']);
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            unset($_SESSION['deadline_stage1']);
            
            $pattern = [];
            foreach ($_POST['rows'] as $i => $row) {
                for ($j = 0; $j < 5; $j++) $pattern[$i][$j] = isset($row[$j]) ? 1 : 0;
            }
            
            $session = new GameSession(['user_id' => $this->userId, 'pattern' => $pattern]);
            $this->repository->save($session);
            header('Location: index.php?action=pattern_game&step=stage2');
            exit;
        }
        ob_start();
        require __DIR__ . '/../../views/game/stage1.php';
        return ob_get_clean();
    }

    public function handleStage2(): string {
        $session = $this->repository->findActiveSession($this->userId);
        if (!$session) { header('Location: index.php?action=pattern_game'); exit; }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $status = $this->service->processAttempt($session, $_POST['guess'] ?? []);
            if ($status !== 'in_progress') {
                header("Location: index.php?action=pattern_game&result=$status");
                exit;
            }
        }
        
        ob_start();
        require __DIR__ . '/../../views/game/stage2.php';
        return ob_get_clean();
    }
}