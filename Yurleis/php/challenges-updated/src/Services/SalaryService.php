<?php

namespace App\Services;

use App\Models\SalaryRecord;
use App\Repositories\SalaryRecordRepository;

require_once __DIR__ . '/../../overtime_rules.php';

class SalaryService
{
    private SalaryRecordRepository $salaryRepository;

    public function __construct(SalaryRecordRepository $repository)
    {
        $this->salaryRepository = $repository;
    }
    private function calculateTax(float $baseSalary): float
    {
        if ($baseSalary < 1000) {
            return 0.0;
        } elseif ($baseSalary <= 2000) {
            return $baseSalary * 0.10;
        }
        return $baseSalary * 0.20;
    }

    private function calculateHealthInsurance(float $baseSalary): float
    {
        return $baseSalary * 0.05;
    }

    private function calculateBonus(float $baseSalary): float
    {
        if ($baseSalary > 0) {
            return (float) rand(100, 500);
        }
        return 0.0;
    }

    // State Logic (replaces 'operations.php' for POST data handling)
    public function updateState(SalaryRecord $state, array $postData): SalaryRecord
    {
        $action = $postData['action'] ?? '';

        // 1. Update Base Salary
        if (isset($postData['base_salary']) && $postData['base_salary'] !== '') {
            $state->setBaseSalary((float) $postData['base_salary']);
        }

        // 2. Update number of rows
        if (isset($postData['rows'])) {
            $state->setInputRows((int) $postData['rows']);
        }
        $rows = $state->getInputRows();

        // 3. Update overtime data
        $state->setInputDates($postData['date_overtime'] ?? $state->getInputDates());
        $state->setInputStartTimes($postData['start_time_overtime'] ?? $state->getInputStartTimes());
        $state->setInputEndTimes($postData['end_time_overtime'] ?? $state->getInputEndTimes());
        
        // 4. Handle add/remove row actions
        if ($action === 'add_overtime') {
            $state->setInputRows($rows + 1);
        } elseif ($action === 'remove_overtime' && $rows > 0) {
            $newRows = $rows - 1;
            $state->setInputRows($newRows);
            
            // Trim arrays to new size (using Setters)
            $state->setInputDates(array_slice($state->getInputDates(), 0, $newRows));
            $state->setInputStartTimes(array_slice($state->getInputStartTimes(), 0, $newRows));
            $state->setInputEndTimes(array_slice($state->getInputEndTimes(), 0, $newRows));
        }

        $state->setErrors([]);
        return $state;
    }

    public function calculateSalary(SalaryRecord $state): SalaryRecord
    {
        $bs = $state->getBaseSalary();
        $state->setErrors([]);

        // Validate base salary
        if ($bs < 1 || $bs > 9999999) {
            $state->setErrors(['base_salary' => 'Base salary must be between 1 and 9,999,999.']);
            $state->setHasResults(false);
            return $state;
        }

        // --- Base Salary Calculations ---
        $state->setTax($this->calculateTax($bs));
        $state->setHealthInsurance($this->calculateHealthInsurance($bs));
        $state->setBonus($this->calculateBonus($bs));
        $finalBaseNet = $bs - $state->getTax() - $state->getHealthInsurance() + $state->getBonus();
        $state->setFinalBaseNet($finalBaseNet);

        // --- Overtime Calculations ---
        $state->setOvertimeDetails([]);
        $state->setTotalOvertimePayment(0.0);

        $rows = $state->getInputRows();
        
        if ($rows > 0) {
            $hourlyRate = $bs / 160;

            for ($i = 0; $i < $rows; $i++) {
                $d = $state->getInputDates()[$i] ?? '';
                $st = $state->getInputStartTimes()[$i] ?? '';
                $et = $state->getInputEndTimes()[$i] ?? '';

                if ($d === '' || $st === '' || $et === '') {
                    continue;
                }
                
                // NOTE: 'get_overtime_hours_by_rules' comes from the 'overtime_rules.php' file
                $hoursData = get_overtime_hours_by_rules($d, $st, $et);
                $hours = $hoursData['overtime_hours'];
                $nightHours = $hoursData['night_hours'];
                $sundayHours = $hoursData['sunday_hours'];

                if ($hours <= 0) {
                    continue;
                }

                $basePay = $hourlyRate * $hours;
                $extraPay = 0.0;
                $extra_labels = [];

                                if ($sundayHours > 0) {
                                    $extraSunday = 0.50 * $hourlyRate * $sundayHours;
                                    $extraPay += $extraSunday;
                                    $extra_labels[] = sprintf(
                                        "+50%% Sunday/Holiday premium on %.2f Sunday overtime hours",
                                        $sundayHours
                                    );
                                }

                                if ($nightHours > 0) {
                                    $extraNight = 0.25 * $hourlyRate * $nightHours;
                                    $extraPay += $extraNight;
                                    $extra_labels[] = sprintf(
                                        "+25%% Night shift premium on %.2f night overtime hours",
                                        $nightHours
                                    );
                                }

                $total = $basePay + $extraPay;
                $details = $state->getOvertimeDetails();
                
                $details[] = [
                    'date' => $d,
                    'hours' => $hours,
                    'base_rate' => $hourlyRate,
                    'extra_labels' => $extra_labels,
                    'total' => $total,
                    'base_pay' => $basePay,
                    'extra_pay' => $extraPay,
                    'extra_sunday' => $extraSunday,
                    'extra_night' => $extraNight,
                ];
                $state->setOvertimeDetails($details);
                $state->setTotalOvertimePayment($state->getTotalOvertimePayment() + $total);
            }
        }

        // Final net salary
        $state->setGrandTotalSalary($state->getFinalBaseNet() + $state->getTotalOvertimePayment());
        $state->setHasResults(true);

        // PERSISTENCE (CRUD Create/Update)
        if ($state->getUserId()) {
            $this->salaryRepository->save($state);
        }
        
        return $state;
    }

    // CRUD Read and Delete methods
    public function getUserRecords(int $userId): array
    {
        return $this->salaryRepository->findAllByUserId($userId);
    }
    
    public function getRecord(int $id): ?SalaryRecord
    {
        return $this->salaryRepository->findById($id);
    }
    
    public function deleteRecord(int $id): bool
    {
        return $this->salaryRepository->delete($id);
    }

    public function calculateAndSave(int $userId, array $postData, SalaryRecord $state): SalaryRecord
    {
        $state = $this->updateState($state, $postData);
        $state->setUserId($userId);
        return $this->calculateSalary($state);
    }

    public function saveRecord(SalaryRecord $state): void
    {
        $this->salaryRepository->save($state);
    }
}