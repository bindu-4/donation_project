<?php
require_once '../config/database.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'donor') {
    header("Location: ../login.php");
    exit;
}
$success = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);
    $email_check = $conn->prepare("SELECT email FROM users WHERE user_id=?");
    $email_check->bind_param("i", $_SESSION['user_id']);
    $email_check->execute();
    $email = $email_check->get_result()->fetch_assoc()['email'];

    $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, subject, message, submitted_at) VALUES (?, ?, ?, ?, NOW())");
    $stmt->bind_param("ssss", $_SESSION['full_name'], $email, $subject, $message);
    if ($stmt->execute()) { $success = true; }
}
require_once '../includes/header.php';
?>
<h2>Contact Us</h2>
<?php if ($success): ?>
    <p class="msg-success">Your message has been sent successfully. Thank you!</p>
<?php endif; ?>
<form method="POST" id="contactForm">
    <label>Subject</label>
    <input type="text" name="subject" id="subject" required>
    <span class="field-error" id="subject_error"></span>

    <label>Message</label>
    <textarea name="message" id="message" rows="5" required></textarea>
    <span class="field-error" id="message_error"></span>

    <button type="submit">Send</button>
</form>
<?php require_once '../includes/footer.php'; ?>