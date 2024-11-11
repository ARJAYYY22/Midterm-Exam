<?php
session_start();

if (!isset($_SESSION['students'])) {
    $_SESSION['students'] = [];
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deleteId'])) {
    $deleteId = $_POST['deleteId'];
    
    
    $_SESSION['students'] = array_filter($_SESSION['students'], fn($student) => $student['id'] !== $deleteId);
    
   
    header("Location: register.php");
    exit;
}


$studentToDelete = null;
if (isset($_GET['studentId'])) {
    foreach ($_SESSION['students'] as $student) {
        if ($student['id'] === $_GET['studentId']) {
            $studentToDelete = $student;
            break;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete a Student</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding: 20px;
        }
        .breadcrumb {
            background-color: #f8f9fa;
        }
        .card {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Delete a Student</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="../dashboard">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="register.php">Register Student</a></li>
                <li class="breadcrumb-item active" aria-current="page">Delete Student</li>
            </ol>
        </nav>
        
        <div class="card">
            <div class="card-body">
                <?php if ($studentToDelete): ?>
                    <p>Are you sure you want to delete the following student record?</p>
                    <ul>
                        <li><strong>Student ID:</strong> <?php echo htmlspecialchars($studentToDelete['id']); ?></li>
                        <li><strong>First Name:</strong> <?php echo htmlspecialchars($studentToDelete['first_name']); ?></li>
                        <li><strong>Last Name:</strong> <?php echo htmlspecialchars($studentToDelete['last_name']); ?></li>
                    </ul>
                    <form action="delete.php" method="POST" class="d-inline">
                        <input type="hidden" name="deleteId" value="<?php echo htmlspecialchars($studentToDelete['id']); ?>">
                        <button type="submit" class="btn btn-danger">Delete Student Record</button>
                    </form>
                    <a href="register.php" class="btn btn-secondary">Cancel</a>
                <?php else: ?>
                    <p class="alert alert-danger">Student not found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
