<?php
require_once '../config/database.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$campaigns = $conn->query("SELECT * FROM campaigns ORDER BY created_at DESC");
require_once '../includes/header.php';
?>

<h2>Manage Campaigns</h2>
<a class="btn" href="add_campaign.php">+ Add New Campaign</a>

<table>
<tr><th>Title</th><th>Goal</th><th>Raised</th><th>Status</th><th>Actions</th></tr>
<?php while ($c = $campaigns->fetch_assoc()): ?>
<tr>
    <td><?php echo htmlspecialchars($c['title']); ?></td>
    <td>Rs. <?php echo number_format($c['goal_amount'], 2); ?></td>
    <td>Rs. <?php echo number_format($c['raised_amount'], 2); ?></td>
    <td><?php echo htmlspecialchars($c['status']); ?></td>
    <td>
        <a href="edit_campaign.php?campaign_id=<?php echo $c['campaign_id']; ?>">Edit</a> |
        <a href="delete_campaign.php?campaign_id=<?php echo $c['campaign_id']; ?>" onclick="return confirm('Are you sure you want to delete this campaign?');">Delete</a>
    </td>
</tr>
<?php endwhile; ?>
</table>

<?php require_once '../includes/footer.php'; ?>