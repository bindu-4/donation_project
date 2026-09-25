<?php
require_once '../config/database.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$error = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $goal_amount = floatval($_POST['goal_amount']);
    $status = $_POST['status'];

    if ($title === "" || $goal_amount <= 0) {
        $error = "Please fill all fields correctly.";
    } else {
        $stmt = $conn->prepare("INSERT INTO campaigns (title, description, goal_amount, raised_amount, status, created_at) VALUES (?, ?, ?, 0, ?, NOW())");
        $stmt->bind_param("ssds", $title, $description, $goal_amount, $status);
        if ($stmt->execute()) {
            header("Location: campaigns.php");
            exit;
        } else {
            $error = "Failed to add campaign.";
        }
    }
}

require_once '../includes/header.php';
?>

<h2>Add New Campaign</h2>
<?php if ($error): ?><p class="msg-error"><?php echo htmlspecialchars($error); ?></p><?php endif; ?>

<form method="POST">
    <label>Title</label>
    <input type="text" name="title" required>

    <label>Description</label>
    <textarea name="description" rows="4" required></textarea>

    <label>Goal Amount (Rs.)</label>
    <input type="number" name="goal_amount" min="1" step="0.01" required>

    <label>Status</label>
    <select name="status">
        <option value="active">Active</option>
        <option value="completed">Completed</option>
    </select>

    <button type="submit">Add Campaign</button>
</form>

<?php require_once '../includes/footer.php'; ?>