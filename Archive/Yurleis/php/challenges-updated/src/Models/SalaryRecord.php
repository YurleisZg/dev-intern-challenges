<?php

namespace App\Models;

class SalaryRecord
{
    // DB Fields
    private ?int $id = null;
    private ?int $userId = null; 
    private ?string $createdAt = null; 

    // Input Fields
    private float $baseSalary = 0.0;
    private int $inputRows = 0; 
    private array $inputDates = []; 
    private array $inputStartTimes = []; 
    private array $inputEndTimes = []; 

    // Result Fields
    private float $tax = 0.0;
    private float $healthInsurance = 0.0;
    private float $bonus = 0.0;
    private float $finalBaseNet = 0.0;
    private float $totalOvertimePayment = 0.0;
    private float $grandTotalSalary = 0.0;
    private array $overtimeDetails = []; 

    // State / Non-DB Fields
    private array $errors = [];
    private bool $hasResults = false;

    // ================= GETTERS =================
    public function getId(): ?int { return $this->id; }
    public function getUserId(): ?int { return $this->userId; }
    public function getCreatedAt(): ?string { return $this->createdAt; }
    
    public function getBaseSalary(): float { return $this->baseSalary; }
    public function getInputRows(): int { return $this->inputRows; }
    public function getInputDates(): array { return $this->inputDates; }
    public function getInputStartTimes(): array { return $this->inputStartTimes; }
    public function getInputEndTimes(): array { return $this->inputEndTimes; }
    
    public function getTax(): float { return $this->tax; }
    public function getHealthInsurance(): float { return $this->healthInsurance; }
    public function getBonus(): float { return $this->bonus; }
    public function getFinalBaseNet(): float { return $this->finalBaseNet; }
    public function getTotalOvertimePayment(): float { return $this->totalOvertimePayment; }
    public function getGrandTotalSalary(): float { return $this->grandTotalSalary; }
    public function getOvertimeDetails(): array { return $this->overtimeDetails; }

    public function getErrors(): array { return $this->errors; }
    public function hasResults(): bool { return $this->hasResults; }

    // ================= SETTERS =================
    // Using 'self' for return type and method chaining
    public function setId(?int $id): self { $this->id = $id; return $this; }
    public function setUserId(?int $userId): self { $this->userId = $userId; return $this; }
    public function setCreatedAt(?string $createdAt): self { $this->createdAt = $createdAt; return $this; }

    public function setBaseSalary(float $baseSalary): self { $this->baseSalary = $baseSalary; return $this; }
    public function setInputRows(int $inputRows): self { $this->inputRows = $inputRows; return $this; }
    public function setInputDates(array $inputDates): self { $this->inputDates = $inputDates; return $this; }
    public function setInputStartTimes(array $inputStartTimes): self { $this->inputStartTimes = $inputStartTimes; return $this; }
    public function setInputEndTimes(array $inputEndTimes): self { $this->inputEndTimes = $inputEndTimes; return $this; }
    
    public function setTax(float $tax): self { $this->tax = $tax; return $this; }
    public function setHealthInsurance(float $healthInsurance): self { $this->healthInsurance = $healthInsurance; return $this; }
    public function setBonus(float $bonus): self { $this->bonus = $bonus; return $this; }
    public function setFinalBaseNet(float $finalBaseNet): self { $this->finalBaseNet = $finalBaseNet; return $this; }
    public function setTotalOvertimePayment(float $totalOvertimePayment): self { $this->totalOvertimePayment = $totalOvertimePayment; return $this; }
    public function setGrandTotalSalary(float $grandTotalSalary): self { $this->grandTotalSalary = $grandTotalSalary; return $this; }
    public function setOvertimeDetails(array $overtimeDetails): self { $this->overtimeDetails = $overtimeDetails; return $this; }

    public function setErrors(array $errors): self { $this->errors = $errors; return $this; }
    public function setHasResults(bool $hasResults): self { $this->hasResults = $hasResults; return $this; }


    // ================= UTILITY METHODS =================
    
    public function toArray(): array
    {
        // Use getters to obtain data from private properties
        return [
            'id' => $this->getId(),
            'userId' => $this->getUserId(),
            'createdAt' => $this->getCreatedAt(),
            'baseSalary' => $this->getBaseSalary(),
            
            // Mapping to previous form/session names for the view
            'rows' => $this->getInputRows(), 
            'date' => $this->getInputDates(),
            'start_time' => $this->getInputStartTimes(),
            'end_time' => $this->getInputEndTimes(),
            
            'tax' => $this->getTax(),
            'health_insurance' => $this->getHealthInsurance(),
            'bonus' => $this->getBonus(),
            'final_base_net' => $this->getFinalBaseNet(),
            'overtime_details' => $this->getOvertimeDetails(),
            'total_overtime_payment' => $this->getTotalOvertimePayment(),
            'grand_total_salary' => $this->getGrandTotalSalary(),
            
            'errors' => $this->getErrors(),
            'has_results' => $this->hasResults(),
        ];
    }

    public static function fromArray(array $data): self
    {
        $model = new self();
        $reflection = new \ReflectionClass($model);

        foreach ($data as $key => $value) {
            // Mapping snake_case keys and old keys (session/form) to Setters
            $setterName = 'set' . ucfirst(lcfirst(str_replace('_', '', ucwords($key, '_'))));

            if ($key === 'rows') {
                $setterName = 'setInputRows';
            } elseif ($key === 'date') {
                $setterName = 'setInputDates';
            } elseif ($key === 'start_time') {
                $setterName = 'setInputStartTimes';
            } elseif ($key === 'end_time') {
                $setterName = 'setInputEndTimes';
            } elseif ($key === 'has_results') { 
                $setterName = 'setHasResults';
            }

            if ($reflection->hasMethod($setterName)) {
                $model->$setterName($value);
            }
        }
        return $model;
    }
}