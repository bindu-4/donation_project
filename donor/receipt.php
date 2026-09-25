<?php
require_once '../config/database.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'donor') {
    header("Location: ../login.php");
    exit;
}
$donation_id = intval($_GET['donation_id'] ?? 0);
$stmt = $conn->prepare("SELECT d.*, c.title, r.receipt_number, r.issued_date
    FROM donations d
    JOIN campaigns c ON d.campaign_id = c.campaign_id
    JOIN donation_receipts r ON r.donation_id = d.donation_id
    WHERE d.donation_id = ? AND d.user_id = ?");
$stmt->bind_param("ii", $donation_id, $_SESSION['user_id']);
$stmt->execute();
$r = $stmt->get_result()->fetch_assoc();
if (!$r) { die("Receipt vetiyena."); }
require_once '../includes/header.php';
?>
<h2>Donation Receipt</h2>
<div class="card">
    <p><strong>Receipt No:</strong> <?php echo htmlspecialchars($r['receipt_number']); ?></p>
    <p><strong>Donor:</strong> <?php echo htmlspecialchars($_SESSION['full_name']); ?></p>
    <p><strong>Campaign:</strong> <?php echo htmlspecialchars($r['title']); ?></p>
    <p><strong>Amount:</strong> Rs. <?php echo number_format($r['amount'], 2); ?></p>
    <p><strong>Payment Method:</strong> <?php echo htmlspecialchars($r['payment_method']); ?></p>
    <p><strong>Issued Date:</strong> <?php echo date("d M Y, h:i A", strtotime($r['issued_date'])); ?></p>
</div>
<a class="btn" href="my_donations.php">Back to My Donations</a>
<?php require_once '../includes/footer.php'; ?>