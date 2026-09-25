<?php
require_once '../config/database.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$messages = $conn->query("SELECT * FROM contact_messages ORDER BY submitted_at DESC");
require_once '../includes/header.php';
?>

<h2>Contact Messages</h2>
<?php while ($m = $messages->fetch_assoc()): ?>
<div class="card">
    <p><strong><?php echo htmlspecialchars($m['name']); ?></strong> (<?php echo htmlspecialchars($m['email']); ?>)</p>
    <p><strong>Subject:</strong> <?php echo htmlspecialchars($m['subject']); ?></p>
    <p><?php echo nl2br(htmlspecialchars($m['message'])); ?></p>
    <p><small><?php echo date("d M Y, h:i A", strtotime($m['submitted_at'])); ?></small></p>
</div>
<?php endwhile; ?>

<?php require_once '../includes/footer.php'; ?>