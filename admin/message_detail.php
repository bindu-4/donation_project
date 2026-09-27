<?php
require_once '../config/database.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$message_id = intval($_GET['message_id'] ?? 0);
$stmt = $conn->prepare("SELECT * FROM contact_messages WHERE message_id=?");
$stmt->bind_param("i", $message_id);
$stmt->execute();
$msg = $stmt->get_result()->fetch_assoc();
if (!$msg) { die("Message not found."); }

// Automatically mark as read when admin opens it
if (!$msg['is_read']) {
    $upd = $conn->prepare("UPDATE contact_messages SET is_read=1 WHERE message_id=?");
    $upd->bind_param("i", $message_id);
    $upd->execute();
}

$success = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reply = trim($_POST['admin_reply']);
    if ($reply !== "") {
        $rep = $conn->prepare("UPDATE contact_messages SET admin_reply=?, replied_at=NOW() WHERE message_id=?");
        $rep->bind_param("si", $reply, $message_id);
        $rep->execute();
        $success = true;
        $stmt->execute();
        $msg = $stmt->get_result()->fetch_assoc();
    }
}

require_once '../includes/header.php';
?>

<h2>Message from <?php echo htmlspecialchars($msg['name']); ?></h2>

<div class="card">
    <p><strong>Email:</strong> <?php echo htmlspecialchars($msg['email']); ?></p>
    <p><strong>Subject:</strong> <?php echo htmlspecialchars($msg['subject']); ?></p>
    <p><strong>Message:</strong><br><?php echo nl2br(htmlspecialchars($msg['message'])); ?></p>
    <p><small><?php echo date("d M Y, h:i A", strtotime($msg['submitted_at'])); ?></small></p>
</div>

<?php if ($msg['admin_reply']): ?>
<div class="card">
    <p><strong>Your Reply:</strong></p>
    <p><?php echo nl2br(htmlspecialchars($msg['admin_reply'])); ?></p>
    <p><small>Replied on <?php echo date("d M Y, h:i A", strtotime($msg['replied_at'])); ?></small></p>
</div>
<?php else: ?>

<?php if ($success): ?><p class="msg-success">Reply saved.</p><?php endif; ?>

<h3>Write a Reply</h3>
<form method="POST" id="replyForm">
    <label>Reply Message</label>
    <textarea name="admin_reply" id="admin_reply" rows="5" required></textarea>
    <span class="field-error" id="reply_error"></span>
    <button type="submit">Save Reply</button>
</form>

<?php endif; ?>

<a class="btn" href="messages.php">Back to Messages</a>

<?php require_once '../includes/footer.php'; ?>