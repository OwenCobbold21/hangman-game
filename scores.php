<?php
session_start();

// if no username is in session send them back to start
if (!isset($_SESSION['username'])) {
    header("Location: start.php");
    exit;
}

require_once './includes/library.php';
$pdo = connectdb();
