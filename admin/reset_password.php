<?php
require_once '../config/database.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$user_id = intval($_GET['user_id'] ?? 0);
$stmt = $conn->prepare("SELECT user_id, full_name, email FROM users WHERE user_id=? AND role='donor'");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$donor = $stmt->get_result()->fetch_assoc();
if (!$donor) { die("Donor not found."); }

$error = "";
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if (strlen($new_password) < 6) {
        $error = "Password must be at least 6 characters.";
    } elseif ($new_password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        $hashed = password_hash($new_password, PASSWORD_DEFAULT);
        $upd = $conn->prepare("UPDATE users SET password=? WHERE user_id=?");
        $upd->bind_param("si", $hashed, $user_id);
        if ($upd->execute()) {
            $success = true;
        } else {
            $error = "Failed to reset password. Please try again.";
        }
    }
}

require_once '../includes/header.php';
?>

<h2>Reset Password for <?php echo htmlspecialchars($donor['full_name']); ?></h2>
<p><strong>Email:</strong> <?php echo htmlspecialchars($donor['email']); ?></p>

<?php if ($success): ?>
    <p class="msg-success">Password has been reset successfully. Please inform the donor of their new password.</p>
<?php endif; ?>

<?php if ($error): ?>
    <p class="msg-error"><?php echo htmlspecialchars($error); ?></p>
<?php endif; ?>

<form method="POST" id="resetPasswordForm">
    <label>New Password</label>
    <input type="password" name="new_password" id="new_password" required>
    <span class="field-error" id="new_password_error"></span>

    <label>Confirm New Password</label>
    <input type="password" name="confirm_password" id="confirm_password" required>
    <span class="field-error" id="confirm_password_error"></span>

    <button type="submit">Reset Password</button>
</form>

<a class="btn" href="donors.php">Back to Donors</a>

<?php require_once '../includes/footer.php'; ?>