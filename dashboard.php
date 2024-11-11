<?php
// Start session at the very beginning of the script
session_start();

// Include functions.php to access guard function and other authentication functions
require 'functions.php';

// Prevent caching of the page after logout to prevent back button access
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
header("Pragma: no-cache"); // HTTP 1.0.
header("Expires: 0"); // Proxies.

if (!isset($_SESSION['user_email'])) {
    // Redirect to login page if not logged in
    header("Location: index.php");
    exit;
}// This will redirect to index.php if the user is not logged in

$pageTitle = "Dashboard Page";
$cssfile = 'css/dash.css';  // Correct path to CSS file
include("header.php");  // Include header
?>

<div class="container">
    <h2>Welcome to the System: <?php echo htmlspecialchars(getUserEmail()); ?></h2>

    <!-- Logout button linking to logout.php -->
    <a href="logout.php" class="btn btn-danger logout-btn">Logout</a>

    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    Add a Subject
                </div>
                <div class="card-body">
                    <p>This section allows you to add a new subject in the system. Click the button below to proceed with the adding process.</p>
                    <a href="subject/add.php" class="btn btn-primary">Add New Subject</a>

                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    Register a Student
                </div>
                <div class="card-body">
                    <p>This section allows you to register a new student in the system. Click the button below to proceed with the registration process.</p>
                    <!-- Button that redirects to the student registration page -->
                    <a href="student/register.php" class="btn btn-primary">Register New Student</a>

                </div>
            </div>
        </div>
    </div>
</div>

<?php
include("footer.php");  // Include footer
?>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
