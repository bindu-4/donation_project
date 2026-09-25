<?php
require_once '../config/database.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$donations = $conn->query("SELECT d.donation_id, d.amount, d.payment_method, d.donation_date, u.full_name, u.email, c.title
    FROM donations d
    JOIN users u ON d.user_id = u.user_id
    JOIN campaigns c ON d.campaign_id = c.campaign_id
    ORDER BY d.donation_date DESC");

require_once '../includes/header.php';
?>

<h2>All Donations</h2>
<table>
<tr><th>Donor</th><th>Email</th><th>Campaign</th><th>Amount</th><th>Method</th><th>Date</th></tr>
<?php while ($d = $donations->fetch_assoc()): ?>
<tr>
    <td><?php echo htmlspecialchars($d['full_name']); ?></td>
    <td><?php echo htmlspecialchars($d['email']); ?></td>
    <td><?php echo htmlspecialchars($d['title']); ?></td>
    <td>Rs. <?php echo number_format($d['amount'], 2); ?></td>
    <td><?php echo htmlspecialchars($d['payment_method']); ?></td>
    <td><?php echo date("d M Y, h:i A", strtotime($d['donation_date'])); ?></td>
</tr>
<?php endwhile; ?>
</table>

<?php require_once '../includes/footer.php'; ?>