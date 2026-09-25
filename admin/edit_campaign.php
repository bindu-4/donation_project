<?php
require_once '../config/database.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$campaign_id = intval($_GET['campaign_id'] ?? 0);
$stmt = $conn->prepare("SELECT * FROM campaigns WHERE campaign_id=?");
$stmt->bind_param("i", $campaign_id);
$stmt->execute();
$campaign = $stmt->get_result()->fetch_assoc();
if (!$campaign) { die("Campaign not found."); }

$error = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $goal_amount = floatval($_POST['goal_amount']);
    $status = $_POST['status'];

    if ($title === "" || $goal_amount <= 0) {
        $error = "Please fill all fields correctly.";
    } else {
        $upd = $conn->prepare("UPDATE campaigns SET title=?, description=?, goal_amount=?, status=? WHERE campaign_id=?");
        $upd->bind_param("ssdsi", $title, $description, $goal_amount, $status, $campaign_id);
        if ($upd->execute()) {
            header("Location: campaigns.php");
            exit;
        } else {
            $error = "Failed to update campaign.";
        }
    }
}

require_once '../includes/header.php';
?>

<h2>Edit Campaign</h2>
<?php if ($error): ?><p class="msg-error"><?php echo htmlspecialchars($error); ?></p><?php endif; ?>

<form method="POST">
    <label>Title</label>
    <input type="text" name="title" value="<?php echo htmlspecialchars($campaign['title']); ?>" required>

    <label>Description</label>
    <textarea name="description" rows="4" required><?php echo htmlspecialchars($campaign['description']); ?></textarea>

    <label>Goal Amount (Rs.)</label>
    <input type="number" name="goal_amount" min="1" step="0.01" value="<?php echo $campaign['goal_amount']; ?>" required>

    <label>Status</label>
    <select name="status">
        <option value="active" <?php if ($campaign['status'] === 'active') echo 'selected'; ?>>Active</option>
        <option value="completed" <?php if ($campaign['status'] === 'completed') echo 'selected'; ?>>Completed</option>
    </select>

    <button type="submit">Update Campaign</button>
</form>

<?php require_once '../includes/footer.php'; ?>