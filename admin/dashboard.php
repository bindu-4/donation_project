<?php
require_once '../config/database.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$totalDonations = $conn->query("SELECT COALESCE(SUM(amount),0) AS total FROM donations")->fetch_assoc()['total'];
$totalCampaigns = $conn->query("SELECT COUNT(*) AS total FROM campaigns")->fetch_assoc()['total'];
$totalDonors = $conn->query("SELECT COUNT(*) AS total FROM users WHERE role='donor'")->fetch_assoc()['total'];
$recentDonations = $conn->query("SELECT d.amount, d.donation_date, u.full_name, c.title
    FROM donations d
    JOIN users u ON d.user_id = u.user_id
    JOIN campaigns c ON d.campaign_id = c.campaign_id
    ORDER BY d.donation_date DESC LIMIT 5");

require_once '../includes/header.php';
?>

<h2>Admin Dashboard</h2>

<div class="card">
    <p><strong>Total Donations Received:</strong> Rs. <?php echo number_format($totalDonations, 2); ?></p>
    <p><strong>Total Campaigns:</strong> <?php echo $totalCampaigns; ?></p>
    <p><strong>Total Donors:</strong> <?php echo $totalDonors; ?></p>
</div>

<h3>Recent Donations</h3>
<table>
<tr><th>Donor</th><th>Campaign</th><th>Amount</th><th>Date</th></tr>
<?php while ($r = $recentDonations->fetch_assoc()): ?>
<tr>
    <td><?php echo htmlspecialchars($r['full_name']); ?></td>
    <td><?php echo htmlspecialchars($r['title']); ?></td>
    <td>Rs. <?php echo number_format($r['amount'], 2); ?></td>
    <td><?php echo date("d M Y, h:i A", strtotime($r['donation_date'])); ?></td>
</tr>
<?php endwhile; ?>
</table>

<?php require_once '../includes/footer.php'; ?>