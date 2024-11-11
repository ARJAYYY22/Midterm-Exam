<?php
// Start session only if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Example users for validation (replace this with actual database logic in production)
$users = [
    "Arjay22@gmail.com"=> "password1",
    "Arjay21@gmail.com" => "password2",
    "Arjay23@gmail.com" => "password3",
    "Arjay24@gmail.com" => "password4",
    "Arjay25@gmail.com" => "password5"
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
        if (!empty($subjectCode) && !empty($subjectName)) {
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
        } else {
            $error_message = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                                  <strong>System Error </strong><br><ul>
                                    <li>Subject Code is Required!</li>
                                    <li>Subject Name is Required!</li></ul>
                                 <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                              </div>";
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

