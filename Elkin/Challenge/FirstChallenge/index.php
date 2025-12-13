<?php
/**
 * FirstChallenge - Main Entry Point
 * 
 * This file serves as the main entry point for the Salary Calculator application.
 * It handles redirecting users based on their authentication status:
 * - Authenticated users → Salary Calculator
 * - Unauthenticated users → Login Page
 */

session_start();

$isAuthenticated = isset($_SESSION['isAuth']) && $_SESSION['isAuth'] === true;

if ($isAuthenticated) {
	// Redirect authenticated users to the salary calculator
	header('Location: ./view/salary-calculator.php');
	exit;
} else {
	// Redirect unauthenticated users to the login page
	header('Location: ./view/login/login.php');
	exit;
}
