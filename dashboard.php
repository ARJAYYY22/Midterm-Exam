<?php   

session_start();


require 'functions.php';


header("Cache-Control: no-cache, no-store, must-revalidate"); 
header("Pragma: no-cache");
header("Expires: 0"); 

if (!isset($_SESSION['user_email'])) {
   
    header("Location: index.php");
    exit;
}

$pageTitle = "Dashboard Page";
$cssfile = 'css/dash.css'; 
include("header.php");  
?>

<div class="container">
    <h2>Welcome to the System: <?php echo htmlspecialchars(getUserEmail()); ?></h2>


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
                  
                    <a href="student/register.php" class="btn btn-primary">Register New Student</a>

                </div>
            </div>
        </div>
    </div>
</div>

<?php
include("footer.php");  
?>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
