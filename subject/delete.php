<?php
session_start();

// Include functions file and header (if needed)
include("../functions.php");
include("../header.php");

if (!isset($_SESSION['user_email'])) {
    // Redirect to login if not logged in
    header("Location: index.php");
    exit;
}

// Check if a subject code is provided via GET
if (isset($_GET['code'])) {
    $code = $_GET['code'];

    // Find the subject in the session array
    $subjectToDelete = null;
    if (!empty($_SESSION['subjects'])) {
        foreach ($_SESSION['subjects'] as $subject) {
            if ($subject['code'] === $code) {
                $subjectToDelete = $subject;
                break;
            }
        }
    }
}

// Process deletion if form is submitted via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['code'])) {
    $code = $_POST['code'];

    // Find and remove the subject from the session array
    if (!empty($_SESSION['subjects'])) {
        foreach ($_SESSION['subjects'] as $key => $subject) {
            if ($subject['code'] === $code) {
                unset($_SESSION['subjects'][$key]);
                $_SESSION['subjects'] = array_values($_SESSION['subjects']); // Re-index the array
                break;
            }
        }
    }
    // Redirect back to add.php after deletion
    header("Location: add.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Delete Subject</title>
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
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mt-5">Delete Subject</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="add.php">Add Subject</a></li>
                <li class="breadcrumb-item active" aria-current="page">Delete Subject</li>
            </ol>
        </nav>

        <div class="card">
            <div class="card-body">
                <?php if ($subjectToDelete): ?>
                    <p>Are you sure you want to delete the following subject record?</p>
                    <ul>
                        <li><strong>Subject Code:</strong> <?= htmlspecialchars($subjectToDelete['code']) ?></li>
                        <li><strong>Subject Name:</strong> <?= htmlspecialchars($subjectToDelete['name']) ?></li>
                    </ul>
                    <form method="POST">
                        <input type="hidden" name="code" value="<?= htmlspecialchars($subjectToDelete['code']) ?>">
                        <button type="button" class="btn btn-secondary" onclick="window.location.href='add.php';">Cancel</button>

                        <button type="submit" class="btn btn-danger">Delete Subject Record</button>
                    </form>
                <?php else: ?>
                    <p class="text-danger">Subject not found.</p>
                    <a href="add.php" class="btn btn-primary">Back to Subject List</a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz4fnFO9gybBogGzA1U5oU2y02l6N/zlW1y5nAYxE6kY5gka4pbF3p7K0U" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-pZjw8f+ua7Kw1TIq5tvbLrQ+0U6Qp6K4l6N/zlW1y5nAYxE6kY5gka4pbF3p7K0U" crossorigin="anonymous"></script>
</body>
</html>
