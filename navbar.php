<style>
    /* Navbar container */
nav {
    background-color: #333; /* Dark background color */
    padding: 10px; /* Padding around the navbar */
    text-align: right; /* Right align the links */
    font-family: Arial, sans-serif; /* Set the font */
}

/* Navbar links */
nav a {
    color: white; /* White text color */
    text-decoration: none; /* Remove underline from links */
    margin: 0 15px; /* Space between the links */
    font-size: 16px; /* Set font size */
}

/* Navbar links on hover */
nav a:hover {
    color: #ff9800; /* Change color to orange on hover */
}

/* Navbar link when active (current page) */
nav a.active {
    color: #ff9800; /* Highlight the active link with orange */
}

</style>
<nav style="text-align: right; padding: 10px;">
    <a href="index.php">Home</a> |
    <a href="shop.php">Shopping</a> |
    <a href="about_us.php">About Us</a> |
    <a href="promotion.php">Promotion</a> |
    <?php if (isset($_SESSION['user_id'])): ?>
        <a href="logout.php">Logout</a>
    <?php else: ?>
        <a href="login.php">Login</a> |
        <a href="registration.php">Register</a>
    <?php endif; ?>
</nav>
