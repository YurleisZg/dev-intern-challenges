<?php

namespace App\Controllers;

use App\Services\SalaryService;
use App\Repositories\SalaryRecordRepository;
use App\config\Database;
use App\Models\SalaryRecord;

class SalaryController
{
    private SalaryService $salaryService;
    private const SESSION_KEY = 'salary_record_state';
    private int $userId;

    private Database $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
        $this->userId = $this->getLoggedInUserId();
        $salaryRepository = new SalaryRecordRepository($database);
        $this->salaryService = new SalaryService($salaryRepository);
    }

    private function getLoggedInUserId(): int
    {
        return $_SESSION['user_id'] ?? 1;
    }

    private function loadState(): SalaryRecord
    {
        $state = new SalaryRecord();
        if (isset($_SESSION[self::SESSION_KEY]) && is_array($_SESSION[self::SESSION_KEY])) {
            $state = SalaryRecord::fromArray($_SESSION[self::SESSION_KEY]);
        }
        $state->setUserId($this->userId);
        return $state;
    }

    private function saveState(SalaryRecord $state): void
    {
        $_SESSION[self::SESSION_KEY] = $state->toArray();
    }

    public function index(): string 
    {
        if (isset($_GET['clear']) && $_GET['clear'] == '1') {
        unset($_SESSION[self::SESSION_KEY]); 
        header('Location: index.php?action=salary');
        exit;
    }
    // ---------------------------------

    $state = $this->loadState();
    $action = $_POST['action'] ?? '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $state = $this->salaryService->updateState($state, $_POST);

            if ($action === 'calculate' || $action === 'remove_overtime' || $action === 'add_overtime') {
                $state = $this->salaryService->calculateSalary($state);
            }

            if ($action === 'calculate' && $state->hasResults()) {
                $this->salaryService->saveRecord($state);
                $this->saveState($state);
                header('Location: index.php?action=salary');
                exit;
            }
            $this->saveState($state);
        }

        return $this->renderView($state); 
    }

    private function renderView(SalaryRecord $state): string 
    {
        $records = $this->salaryService->getUserRecords($this->userId);
        extract($state->toArray());

        ob_start();
        require __DIR__ . '/../../views/salary_page_content.php';
        return ob_get_clean();
    }
    public function viewRecord(int $id): void
    {
        $record = $this->salaryService->getRecord($id);

        if (!$record || $record->getUserId() !== $this->userId) {
            header('Location: index.php');
            exit;
        }
        $this->saveState($record);
        header('Location: index.php');
        exit;
    }

    public function deleteRecord(int $id): void
    {
        $record = $this->salaryService->getRecord($id);

        if ($record && $record->getUserId() === $this->userId) {
            $this->salaryService->deleteRecord($id);
        }

        header('Location: index.php');
        exit;
    }
}