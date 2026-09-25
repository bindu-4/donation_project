<?php
require_once '../config/database.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'donor') {
    header("Location: ../login.php");
    exit;
}
$stmt = $conn->prepare("SELECT d.donation_id, d.amount, d.payment_method, d.donation_date, c.title
    FROM donations d JOIN campaigns c ON d.campaign_id = c.campaign_id
    WHERE d.user_id = ? ORDER BY d.donation_date DESC");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$donations = $stmt->get_result();
require_once '../includes/header.php';
?>
<h2>My Donations</h2>
<table>
<tr><th>Campaign</th><th>Amount</th><th>Method</th><th>Date</th><th>Receipt</th></tr>
<?php while ($d = $donations->fetch_assoc()): ?>
<tr>
    <td><?php echo htmlspecialchars($d['title']); ?></td>
    <td>Rs. <?php echo number_format($d['amount'], 2); ?></td>
    <td><?php echo htmlspecialchars($d['payment_method']); ?></td>
    <td><?php echo date("d M Y, h:i A", strtotime($d['donation_date'])); ?></td>
    <td><a href="receipt.php?donation_id=<?php echo $d['donation_id']; ?>">View</a></td>
</tr>
<?php endwhile; ?>
</table>
<?php require_once '../includes/footer.php'; ?>