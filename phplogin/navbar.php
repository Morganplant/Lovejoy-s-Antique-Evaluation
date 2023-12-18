<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('Location: index.html');
    exit;
}
$isAdmin = $_SESSION['isadmin'];
?>
<nav class="navtop">
    <div>
        <h1>Lovejoy's Antiques</h1>
        <a href="request-evaluation.php"><i class="fas fa-file-alt"></i>Request Evaluation</a>
        <?php if ($isAdmin): ?>
            <a href="requests.php"><i class="fas fa-list"></i>Requests</a>
        <?php endif; ?>
        <a href="profile.php"><i class="fas fa-user-circle"></i>Profile</a>

        <a href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
    </div>
</nav>