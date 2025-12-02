<?php
session_start();

require_once __DIR__ . '/utils.php';

$overtimeRows = handleOvertimeRows();
[$formData, $result] = processSalaryForm($overtimeRows);

// Render
$overtimeRows = (int) $overtimeRows;
require __DIR__ . '/view.php';