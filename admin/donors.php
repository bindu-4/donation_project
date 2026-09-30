<?php
require_once '../config/database.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$donors = $conn->query("SELECT u.user_id, u.full_name, u.email, u.phone, u.created_at,
    COALESCE(SUM(d.amount), 0) AS total_donated
    FROM users u
    LEFT JOIN donations d ON u.user_id = d.user_id
    WHERE u.role = 'donor'
    GROUP BY u.user_id
    ORDER BY u.created_at DESC");

require_once '../includes/header.php';
?>

<h2>Manage Donors</h2>

<table>
<tr><th>Name</th><th>Email</th><th>Phone</th><th>Joined</th><th>Total Donated</th><th>Action</th></tr>
<?php while ($d = $donors->fetch_assoc()): ?>
<tr>
    <td><?php echo htmlspecialchars($d['full_name']); ?></td>
    <td><?php echo htmlspecialchars($d['email']); ?></td>
    <td><?php echo htmlspecialchars($d['phone']); ?></td>
    <td><?php echo date("d M Y", strtotime($d['created_at'])); ?></td>
    <td>Rs. <?php echo number_format($d['total_donated'], 2); ?></td>
    <td><a href="reset_password.php?user_id=<?php echo $d['user_id']; ?>">Reset Password</a></td>
</tr>
<?php endwhile; ?>
</table>

<?php require_once '../includes/footer.php'; ?>