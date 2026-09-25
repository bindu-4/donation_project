<?php
require_once '../config/database.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'donor') {
    header("Location: ../login.php");
    exit;
}
$campaigns = $conn->query("SELECT * FROM campaigns WHERE status='active'");
require_once '../includes/header.php';
?>
<h2>Active Campaigns</h2>
<?php while ($c = $campaigns->fetch_assoc()):
    $percent = $c['goal_amount'] > 0 ? min(100, round(($c['raised_amount'] / $c['goal_amount']) * 100)) : 0;
?>
<div class="card">
    <h3><?php echo htmlspecialchars($c['title']); ?></h3>
    <p><?php echo htmlspecialchars($c['description']); ?></p>
    <div class="progress-bar"><div class="progress-bar-fill" style="width:<?php echo $percent; ?>%"></div></div>
    <p>Raised: Rs. <?php echo number_format($c['raised_amount']); ?> / Rs. <?php echo number_format($c['goal_amount']); ?> (<?php echo $percent; ?>%)</p>
    <a class="btn" href="donate.php?campaign_id=<?php echo $c['campaign_id']; ?>">Donate Now</a>
</div>
<?php endwhile; ?>
<?php require_once '../includes/footer.php'; ?>