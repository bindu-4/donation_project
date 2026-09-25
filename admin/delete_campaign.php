<?php
require_once '../config/database.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$campaign_id = intval($_GET['campaign_id'] ?? 0);
$stmt = $conn->prepare("DELETE FROM campaigns WHERE campaign_id=?");
$stmt->bind_param("i", $campaign_id);
$stmt->execute();

header("Location: campaigns.php");
exit;
?>