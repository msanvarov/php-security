<?php
declare(strict_types=1);

// Production-by-mistake settings: show every error, including stack traces with
// database credentials, file paths, and library versions.
ini_set('display_errors', '1');
error_reporting(E_ALL);

// Trigger a connection error against a misspelled database.
$pdo = new PDO('mysql:host=db;dbname=does_not_exist', 'root', 'wrong-password');
