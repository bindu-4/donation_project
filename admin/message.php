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

<table>
<tr><th>Name</th><th>Subject</th><th>Date</th><th>Status</th><th>Action</th></tr>
<?php while ($m = $messages->fetch_assoc()): ?>
<tr>
    <td><?php echo htmlspecialchars($m['name']); ?></td>
    <td><?php echo htmlspecialchars($m['subject']); ?></td>
    <td><?php echo date("d M Y, h:i A", strtotime($m['submitted_at'])); ?></td>
    <td><?php echo $m['is_read'] ? 'Read' : '<strong>Unread</strong>'; ?></td>
    <td><a href="message_detail.php?message_id=<?php echo $m['message_id']; ?>">View &amp; Reply</a></td>
</tr>
<?php endwhile; ?>
</table>

<?php require_once '../includes/footer.php'; ?>