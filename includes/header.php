<?php if (session_status() === PHP_SESSION_NONE) { session_start(); } ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Donation Management System</title>
<link rel="stylesheet" href="/donation_web/assets/css/style.css">
</head>
<body>
<header class="site-header">
    <h1>Helping Hands DMS</h1>
    <nav>
        <a href="/donation_web/index.php">Home</a>
        <?php if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'donor'): ?>
            <a href="/donation_web/donor/dashboard.php">Dashboard</a>
            <a href="/donation_web/donor/my_donations.php">My Donations</a>
            <a href="/donation_web/donor/contact.php">Contact</a>
            <a href="/donation_web/logout.php">Logout (<?php echo htmlspecialchars($_SESSION['full_name']); ?>)</a>
        <?php else: ?>
            <a href="/donation_web/login.php">Login</a>
            <a href="/donation_web/register.php">Register</a>
        <?php endif; ?>
    </nav>
</header>
<main class="container">