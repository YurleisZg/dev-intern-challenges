<?php

require_once __DIR__ . '/../config/DatabaseConn.php';

class SalaryRecord {
    public static function create(int $userId, float $grossSalary, array $overtimeRecords): int {
        $db = DatabaseConn::getInstance()->getConnection();

        $db->beginTransaction();
        try {
            $stmt = $db->prepare(
                'INSERT INTO salary_records (user_id, gross_salary_input, status) VALUES (?, ?, "Draft")'
            );
            $stmt->execute([$userId, $grossSalary]);
            $recordId = (int) $db->lastInsertId();

            self::insertDetails($db, $recordId, $overtimeRecords);

            $db->commit();
            return $recordId;
        } catch (Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }

    public static function update(int $recordId, int $userId, float $grossSalary, array $overtimeRecords): bool {
        $db = DatabaseConn::getInstance()->getConnection();
        $db->beginTransaction();
        try {
            $update = $db->prepare(
                'UPDATE salary_records SET gross_salary_input = ?, updated_at = NOW() WHERE record_id = ? AND user_id = ?'
            );
            $update->execute([$grossSalary, $recordId, $userId]);

            $deleteDetails = $db->prepare('DELETE FROM salary_records_details WHERE record_id = ?');
            $deleteDetails->execute([$recordId]);

            self::insertDetails($db, $recordId, $overtimeRecords);

            $db->commit();
            return true;
        } catch (Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }

    public static function delete(int $recordId, int $userId): bool {
        $db = DatabaseConn::getInstance()->getConnection();
        $stmt = $db->prepare('DELETE FROM salary_records WHERE record_id = ? AND user_id = ?');
        return $stmt->execute([$recordId, $userId]);
    }

    public static function findWithDetails(int $recordId, int $userId): ?array {
        $db = DatabaseConn::getInstance()->getConnection();
        $stmt = $db->prepare('SELECT record_id, user_id, gross_salary_input, status, created_at, updated_at FROM salary_records WHERE record_id = ? AND user_id = ?');
        $stmt->execute([$recordId, $userId]);
        $record = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$record) {
            return null;
        }

        $detailStmt = $db->prepare('SELECT shift_date AS date, start_time AS start, end_time AS end FROM salary_records_details WHERE record_id = ? ORDER BY shift_date, start_time');
        $detailStmt->execute([$recordId]);
        $record['details'] = $detailStmt->fetchAll(PDO::FETCH_ASSOC);

        return $record;
    }

    public static function listByUser(int $userId): array {
        $db = DatabaseConn::getInstance()->getConnection();
        $stmt = $db->prepare(
            'SELECT r.record_id, r.gross_salary_input, r.status, r.created_at, r.updated_at, COUNT(d.detail_id) AS detail_count
             FROM salary_records r
             LEFT JOIN salary_records_details d ON d.record_id = r.record_id
             WHERE r.user_id = ?
             GROUP BY r.record_id, r.gross_salary_input, r.status, r.created_at, r.updated_at
             ORDER BY r.updated_at DESC'
        );
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private static function insertDetails(\PDO $db, int $recordId, array $overtimeRecords): void {
        $insert = $db->prepare(
            'INSERT INTO salary_records_details (record_id, shift_date, start_time, end_time) VALUES (?, ?, ?, ?)'
        );

        foreach ($overtimeRecords as $record) {
            $date = $record['date'] ?? null;
            $start = $record['start'] ?? null;
            $end = $record['end'] ?? null;

            if (empty($date) || empty($start) || empty($end)) {
                continue;
            }

            $insert->execute([$recordId, $date, $start, $end]);
        }
    }
}

