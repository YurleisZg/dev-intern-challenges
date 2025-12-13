<?php
session_start();

require_once '../config/DatabaseConn.php';
require_once '../models/SalaryRecordModel.php';
require_once '../utils.php';

if (!isset($_SESSION['isAuth']) || $_SESSION['isAuth'] !== true) {
	header('Location: ./login/login.php');
	exit;
}

$userId = (int) ($_SESSION['user_id'] ?? 0);

// Gestionar filas dinámicas de horas extra
$overtimeRows = handleOvertimeRows();

$currentRecordId = isset($_GET['record_id']) ? (int) $_GET['record_id'] : null;
$flash = null;
$result = null;

// Normaliza detalles de horas extra desde POST
$postedOvertime = [];
if (isset($_POST['overtime_date']) && is_array($_POST['overtime_date'])) {
	foreach ($_POST['overtime_date'] as $idx => $date) {
		$postedOvertime[] = [
			'date' => $date,
			'start' => $_POST['overtime_start'][$idx] ?? null,
			'end' => $_POST['overtime_end'][$idx] ?? null,
		];
	}
}

// Acciones CRUD
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$currentRecordId = isset($_POST['record_id']) ? (int) $_POST['record_id'] : $currentRecordId;
	$grossSalaryInput = (float) ($_POST['gross_salary'] ?? 0);

	if (isset($_POST['delete_record'])) {
		SalaryRecord::delete((int) $_POST['delete_record'], $userId);
		$flash = 'Registro eliminado.';
		$currentRecordId = null;
		$_SESSION['overtime_rows'] = 1;
		$postedOvertime = [];
	} else {
		$shouldCalculate = isset($_POST['calculate']) || isset($_POST['save_record']) || isset($_POST['update_record']);

		if ($currentRecordId && isset($_POST['update_record'])) {
			SalaryRecord::update($currentRecordId, $userId, $grossSalaryInput, $postedOvertime);
			$flash = 'Record updated.';
		} elseif (isset($_POST['save_record'])) {
			$currentRecordId = SalaryRecord::create($userId, $grossSalaryInput, $postedOvertime);
			$flash = 'Record saved.';
		}

		if ($shouldCalculate) {
			$result = computeSalaryResult($grossSalaryInput, $postedOvertime);
		}
	}
}

// Cargar registro seleccionado para edición si aplica
$currentRecord = $currentRecordId ? SalaryRecord::findWithDetails($currentRecordId, $userId) : null;

// Ajustar cantidad de filas cuando se edita un registro guardado
if (empty($_POST) && $currentRecord && isset($currentRecord['details'])) {
	$detailsCount = max(1, count($currentRecord['details']));
	$_SESSION['overtime_rows'] = $detailsCount;
	$overtimeRows = $detailsCount;
}

// Preparar datos para rellenar el formulario
$formData = [
	'gross_salary' => '',
	'overtime_date' => array_fill(0, $overtimeRows, ''),
	'overtime_start' => array_fill(0, $overtimeRows, ''),
	'overtime_end' => array_fill(0, $overtimeRows, ''),
];

if (!empty($postedOvertime)) {
	$formData['gross_salary'] = $_POST['gross_salary'] ?? '';
	foreach ($postedOvertime as $i => $row) {
		$formData['overtime_date'][$i] = $row['date'] ?? '';
		$formData['overtime_start'][$i] = $row['start'] ?? '';
		$formData['overtime_end'][$i] = $row['end'] ?? '';
	}
} elseif ($currentRecord) {
	$formData['gross_salary'] = $currentRecord['gross_salary_input'];
	foreach ($currentRecord['details'] as $i => $row) {
		$formData['overtime_date'][$i] = $row['date'] ?? '';
		$formData['overtime_start'][$i] = $row['start'] ?? '';
		$formData['overtime_end'][$i] = $row['end'] ?? '';
	}
}

// Resultado desde URL directa (sin POST) usando datos guardados
if (!$result && $currentRecord && isset($currentRecord['details'])) {
	$result = computeSalaryResult((float) $currentRecord['gross_salary_input'], $currentRecord['details']);
}

// Listado de registros del usuario
$savedRecords = SalaryRecord::listByUser($userId);

// Render
$overtimeRows = (int) $overtimeRows;
require __DIR__ . '/view.php';