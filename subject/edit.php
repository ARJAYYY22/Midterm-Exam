<?php
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Example users for validation (replace this with actual database logic in production)
$users = [
    "user1@gmail.com" => "password1",
    "user2@gmail.com" => "password2",
    "user3@gmail.com" => "password3",
    "user4@gmail.com" => "password4",
    "user5@gmail.com" => "password5"
];

// Function to validate login credentials
function validate_login($email, $password) {
    global $users;
    return isset($users[$email]) && $users[$email] === $password;
}

// Guard function to check login status and redirect to index if not logged in
// Function to get the logged-in user's email
function getUserEmail() {
    return $_SESSION['user_email'] ?? ''; // Return the user's email if set
}

// Function to process the login form
function process_login_form() {
    $error_message = "";

    // Only process if form is submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Check for empty fields and validate email format
        if (empty($email) || empty($password)) {
            $error_message = "<br>- Email is required<br>- Password is required";
        } elseif (!str_ends_with($email, '@gmail.com')) {
            $error_message = "<br>- Invalid Email format";
        } elseif (!validate_login($email, $password)) {
            $error_message = "<br>- Invalid Email or Password";
        } else {
            // Successful login
            $_SESSION['user_email'] = $email;  // Store user's email in session

            // Redirect to the dashboard page
            header("Location: dashboard.php");
            exit;
        }
    }
    return $error_message;
}

// Optional function to check if already logged in and redirect
function check_login_status() {
    if (isset($_SESSION['user_email'])) {
        header("Location: dashboard.php"); // Redirect to dashboard if already logged in
        exit;
    }
}

// Logout function to destroy the session and redirect to the login page
function logout() {
    // Destroy the session completely
    session_unset(); // Unset all session variables
    session_destroy(); // Destroy the session completely

    // Prevent caching of the page after logout to prevent back button access
    header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
    header("Pragma: no-cache"); // HTTP 1.0.
    header("Expires: 0"); // Proxies.

    // Redirect to the login page
    header("Location: index.php"); // Redirect user to the login page
    exit;
}

// Initialize error message variable for subject management
$error_message = "";

// Function to process the subject form (add or edit subjects)
function process_subject_form() {
    global $error_message;

    // Check if the form is submitted for adding or editing subjects
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['subjectCode']) && isset($_POST['subjectName'])) {
        $subjectCode = $_POST['subjectCode'];
        $subjectName = $_POST['subjectName'];

        // Validate input and add the subject or edit an existing one
        if (empty($subjectCode)) {
            $error_message = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                                  <strong>System Error </strong><br><ul>
                                    <li>Subject Code is required!</li></ul>
                                 <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                              </div>";
        } elseif (empty($subjectName)) {
            $error_message = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                                  <strong>System Error </strong><br><ul>
                                    <li>Subject Name is required!</li></ul>
                                 <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                              </div>";
        } else {
            if (isset($_POST['editSubject'])) { // If we are editing
                $editIndex = $_POST['editSubject']; // Get index of the subject to edit
                $_SESSION['subjects'][$editIndex] = ['code' => $subjectCode, 'name' => $subjectName];
            } else { // Adding new subject
                // Check for duplicate subject code
                $duplicateFound = false;
                foreach ($_SESSION['subjects'] as $subject) {
                    if ($subject['code'] === $subjectCode) {
                        $duplicateFound = true;
                        break;
                    }
                }

                // If duplicate is found, set an error message
                if ($duplicateFound) {
                    $error_message = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                                          <strong>System Error </strong><br><ul>
                                    <li>Duplicate Subject Code</li></ul>
                                          <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                                      </div>";
                } else {
                    // Add the subject to the session array if no duplicate
                    $_SESSION['subjects'][] = ['code' => $subjectCode, 'name' => $subjectName];
                }
            }

            // Redirect to add.php after successful form submission (edit or add)
            if (empty($error_message)) {
                header("Location: add.php"); // Redirect to the Add Subject page
                exit;
            }
        }
    }
}

// Initialize session for subjects if not already set
if (!isset($_SESSION['subjects'])) {
    $_SESSION['subjects'] = [];
}

// Process the form submission (add or edit subject)
process_subject_form();
?>

<html>
<head>
    <title>Edit Subject</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .breadcrumb {
            background-color: #e9ecef;
        }
        .card {
            margin-top: 20px;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mt-5">Edit Subject</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="../dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="add.php">Add Subject</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit Subject</li>
            </ol>
        </nav>

        <!-- Show the error message if there is one -->
        <?= $error_message; ?>

        <div class="card">
            <div class="card-body">
                <form method="POST" action="">
                    <?php
                    // Get the subject index to edit (e.g., from URL query parameter)
                    $editIndex = isset($_GET['editIndex']) ? $_GET['editIndex'] : 0;
                    $subject = $_SESSION['subjects'][$editIndex];
                    ?>

                    <div class="mb-3">
                        <label for="subjectId" class="form-label">Subject Code</label>
                        <input type="text" class="form-control" id="subjectCode" name="subjectCode" value="<?= $subject['code'] ?>" >
                        <input type="hidden" name="editSubject" value="<?= $editIndex ?>"> <!-- Hidden input to track the subject index -->
                    </div>

                    <div class="mb-3">
                        <label for="subjectName" class="form-label">Subject Name</label>
                        <input type="text" class="form-control" id="subjectName" name="subjectName" placeholder="<?= $subject['name'] ?>" >
                    </div>

                    <button type="submit" class="btn btn-primary">Update Subject</button>
                </form>
            </div>
        </div>
    </div>
    <?php include("../footer.php"); ?>
</body>
</html>
