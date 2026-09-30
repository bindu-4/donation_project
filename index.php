<?php
require_once 'config/database.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }

$campaigns = $conn->query("SELECT * FROM campaigns WHERE status='active'");

require_once 'includes/header.php';
?>

<h2>Welcome to Helping Hands</h2>
<p>Support our active campaigns and make a difference.</p>

<div class="category-section">
    <h2>Choose a Cause</h2>
    <div class="category-grid">

        <a href="#active-campaigns" class="category-card active-category">
            <img src="assets/images/flood.jpg" alt="Flood Victims" class="category-image">
            <div class="category-body">
                <div class="category-title">Flood Victims</div>
                <span class="category-badge badge-active">Active</span>
            </div>
        </a>

        <div class="category-card disabled-category">
            <img src="assets/images/education.jpg" alt="Education Support" class="category-image">
            <div class="category-body">
                <div class="category-title">Education Support</div>
                <span class="category-badge">Coming Soon</span>
            </div>
        </div>

        <div class="category-card disabled-category">
            <img src="assets/images/orphanage.jpg" alt="Orphanage" class="category-image">
            <div class="category-body">
                <div class="category-title">Orphanage</div>
                <span class="category-badge">Coming Soon</span>
            </div>
        </div>

        <div class="category-card disabled-category">
            <img src="assets/images/oldage.jpg" alt="Old Age Home" class="category-image">
            <div class="category-body">
                <div class="category-title">Old Age Home</div>
                <span class="category-badge">Coming Soon</span>
            </div>
        </div>

        <div class="category-card disabled-category">
            <img src="assets/images/temple.jpg" alt="Temple Support" class="category-image">
            <div class="category-body">
                <div class="category-title">Temple Support</div>
                <span class="category-badge">Coming Soon</span>
            </div>
        </div>

    </div>
</div>

<h2 id="active-campaigns">Active Campaigns</h2>

<?php if ($campaigns && $campaigns->num_rows > 0): ?>
    <?php while ($c = $campaigns->fetch_assoc()):
        $percent = $c['goal_amount'] > 0 ? min(100, round(($c['raised_amount'] / $c['goal_amount']) * 100)) : 0;
    ?>
    <div class="card">
        <h3><?php echo htmlspecialchars($c['title']); ?></h3>
        <p><?php echo htmlspecialchars($c['description']); ?></p>
        <div class="progress-bar"><div class="progress-bar-fill" style="width:<?php echo $percent; ?>%"></div></div>
        <p>Raised: Rs. <?php echo number_format($c['raised_amount']); ?> / Rs. <?php echo number_format($c['goal_amount']); ?> (<?php echo $percent; ?>%)</p>

        <?php if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'donor'): ?>
            <a class="btn" href="donor/donate.php?campaign_id=<?php echo $c['campaign_id']; ?>">Donate Now</a>
        <?php else: ?>
            <a class="btn" href="login.php">Login to Donate</a>
        <?php endif; ?>
    </div>
    <?php endwhile; ?>
<?php else: ?>
    <p>There are no active campaigns at the moment.</p>
<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>