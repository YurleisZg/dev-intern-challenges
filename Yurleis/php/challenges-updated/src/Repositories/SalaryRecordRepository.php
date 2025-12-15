<?php

namespace App\Repositories;

use App\Models\SalaryRecord;
use App\config\Database;

class SalaryRecordRepository implements SalaryRecordRepositoryInterface
{
    private \PDO $db;
    private const TABLE = 'salary_records';

    public function __construct(Database $database)
    {
        $this->db = $database->getConnection();
    }
    private function mapToModel(array $row): SalaryRecord
    {
        $record = new SalaryRecord();
        
        $record->setId((int)$row['id'])
               ->setUserId((int)$row['user_id'])
               ->setCreatedAt($row['created_at'])
            
               ->setBaseSalary((float)$row['base_salary'])
               ->setTax((float)$row['tax'])
               ->setHealthInsurance((float)$row['health_insurance'])
               ->setBonus((float)$row['bonus'])
               ->setFinalBaseNet((float)$row['final_base_net'])
               ->setTotalOvertimePayment((float)$row['total_overtime_payment'])
               ->setGrandTotalSalary((float)$row['grand_total_salary'])

               ->setInputRows((int)$row['input_rows'])
               ->setInputDates(json_decode($row['input_dates'], true) ?? [])
               ->setInputStartTimes(json_decode($row['input_start_times'], true) ?? [])
               ->setInputEndTimes(json_decode($row['input_end_times'], true) ?? [])
               ->setOvertimeDetails(json_decode($row['overtime_details_json'], true) ?? [])
               
               ->setHasResults(true);
        
        return $record;
    }

    public function save(SalaryRecord $record): int
    {
        // Use Getters to obtain values from the model
        $otDetailsJson = json_encode($record->getOvertimeDetails());
        $inputDatesJson = json_encode($record->getInputDates());
        $inputStartTimesJson = json_encode($record->getInputStartTimes());
        $inputEndTimesJson = json_encode($record->getInputEndTimes());

        if ($record->getId() === null) {
            // CREATE: Save a new record
            $sql = "INSERT INTO " . self::TABLE . " (user_id, base_salary, tax, health_insurance, bonus, final_base_net, total_overtime_payment, grand_total_salary, input_rows, input_dates, input_start_times, input_end_times, overtime_details_json) 
                    VALUES (:userId, :baseSalary, :tax, :healthInsurance, :bonus, :finalBaseNet, :totalOtPayment, :grandTotalSalary, :rows, :dates, :starts, :ends, :otDetails)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                'userId' => $record->getUserId(),
                'baseSalary' => $record->getBaseSalary(),
                'tax' => $record->getTax(),
                'healthInsurance' => $record->getHealthInsurance(),
                'bonus' => $record->getBonus(),
                'finalBaseNet' => $record->getFinalBaseNet(),
                'totalOtPayment' => $record->getTotalOvertimePayment(),
                'grandTotalSalary' => $record->getGrandTotalSalary(),
                'rows' => $record->getInputRows(),
                'dates' => $inputDatesJson,
                'starts' => $inputStartTimesJson,
                'ends' => $inputEndTimesJson,
                'otDetails' => $otDetailsJson,
            ]);
            $record->setId((int)$this->db->lastInsertId());
            return $record->getId();
        } else {
            return $record->getId();
        }
    }

    public function findById(int $id): ?SalaryRecord
    {
        $stmt = $this->db->prepare("SELECT * FROM " . self::TABLE . " WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        return $row ? $this->mapToModel($row) : null;
    }

    public function findAllByUserId(int $userId): array
    {
        $sql = "SELECT * FROM " . self::TABLE . " WHERE user_id = :userId ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['userId' => $userId]);
        
        $records = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $records[] = $this->mapToModel($row);
        }
        return $records;
    }
    
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM " . self::TABLE . " WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}