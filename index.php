<?php
// Prevent caching of the page to prevent back button issues
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
header("Pragma: no-cache"); // HTTP 1.0.
header("Expires: 0"); // Proxies.

session_start();  // Start session'
if (isset($_SESSION['user_email'])) {
    // Redirect to dashboard if already logged in
    header("Location: dashboard.php");
    exit;
}

$pageTitle = "Login Page";
$cssfile = 'css/loginpage.css';  // Correct path to CSS file
include("header.php");           // Include header

// Include functions.php for form validation and error handling
include("functions.php");

// Call the process_login_form function to handle form submission and validation
$error_message = process_login_form();
?>
    <div>
        <!-- Dismissable error message -->
    <?php if (!empty($error_message)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>System Error!</strong> <?php echo $error_message; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

<div class="login-container">
    <h3>Login</h3>
    <div class="container">
        <!-- Add method="POST" and name attributes to the form -->
        <form method="POST" action="">
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="text" class="form-control" id="email" name="email" placeholder="Enter email">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Password">
            </div>
            <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>
    </div>
</div>

<?php
include("footer.php");  // Include footer
?>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
