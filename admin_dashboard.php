<?php
// Start the session
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

// Admin username
$admin_username = $_SESSION['admin'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="admin_styles.css">
</head>
<body>
    <!-- Include the sidebar -->
    <?php include('sidebar.php'); ?>

    <!-- Main content -->
    <div style="margin-left: 270px; padding: 20px;">
        <h1>Welcome, <?php echo htmlspecialchars($admin_username); ?>!</h1>
        <p>You're logged in as an admin.</p>
        <p><a href="admin_logout.php">Logout</a></p>

        <!-- Add your dashboard content here -->
        <p>Admin Dashboard Content Goes Here</p>
    </div>
</body>
</html>
