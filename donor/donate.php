<?php
require_once '../config/database.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'donor') {
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
    $amount = floatval($_POST['amount']);
    $method = $_POST['payment_method'];
    if ($amount <= 0) {
        $error = "Please enter a valid amount.";
    } else {
        $conn->begin_transaction();
        try {
            $ins = $conn->prepare("INSERT INTO donations (user_id, campaign_id, amount, payment_method, donation_date) VALUES (?, ?, ?, ?, NOW())");
            $ins->bind_param("iids", $_SESSION['user_id'], $campaign_id, $amount, $method);
            $ins->execute();
            $donation_id = $conn->insert_id;

            $upd = $conn->prepare("UPDATE campaigns SET raised_amount = raised_amount + ? WHERE campaign_id=?");
            $upd->bind_param("di", $amount, $campaign_id);
            $upd->execute();

            $receipt_number = "RCT-" . date("Ymd") . "-" . $donation_id;
            $rec = $conn->prepare("INSERT INTO donation_receipts (donation_id, receipt_number, issued_date) VALUES (?, ?, NOW())");
            $rec->bind_param("is", $donation_id, $receipt_number);
            $rec->execute();

            $conn->commit();
            header("Location: receipt.php?donation_id=" . $donation_id);
            exit;
        } catch (Exception $e) {
            $conn->rollback();
            $error = "Failed to save donation. Please try again.";
        }
    }
}
require_once '../includes/header.php';
?>
<h2>Donate to: <?php echo htmlspecialchars($campaign['title']); ?></h2>
<?php if ($error): ?><p class="msg-error"><?php echo htmlspecialchars($error); ?></p><?php endif; ?>
<form method="POST" id="donateForm">
    <label>Amount (Rs.)</label>
    <input type="number" name="amount" id="amount" min="1" step="0.01" required>
    <span class="field-error" id="amount_error"></span>

    <label>Payment Method</label>
    <select name="payment_method">
        <option value="Cash">Cash</option>
        <option value="Bank Transfer">Bank Transfer</option>
        <option value="Cheque">Cheque</option>
    </select>

    <button type="submit">Confirm Donation</button>
</form>
<?php require_once '../includes/footer.php'; ?>