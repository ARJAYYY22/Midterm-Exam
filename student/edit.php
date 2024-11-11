<?php
session_start();

if (!isset($_SESSION['students'])) {
    $_SESSION['students'] = [];
}

$studentId = $_GET['studentId'] ?? null;
$student = null;

foreach ($_SESSION['students'] as &$s) {
    if ($s['id'] === $studentId) {
        $student = &$s;
        break;
    }
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $student) {
    $student['first_name'] = htmlspecialchars($_POST['firstName']);
    $student['last_name'] = htmlspecialchars($_POST['lastName']);

    $message = "Student details updated successfully!";

    header("Location: register.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #004a94; }
        .breadcrumb { background-color: #FFFAFA; }
        .form-container { background-color: #4682B4; padding: 20px; border-radius: 5px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1>Edit Student</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="../dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="register.php">Register Student</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit Student</li>
            </ol>
        </nav>

        <?php if (!empty($message)): ?>
            <div class="alert <?php echo (strpos($message, 'successfully') !== false) ? 'alert-info' : 'alert-danger'; ?> alert-dismissible fade show" role="alert">
                <?php echo $message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="form-container mt-3">
            <?php if ($student): ?>
                <form method="POST">
                    <div class="mb-3">
                        <label for="studentId" class="form-label">Student ID</label>
                        <input type="text" class="form-control" id="studentId" value="<?php echo htmlspecialchars($student['id']); ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="firstName" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="firstName" name="firstName" value="<?php echo htmlspecialchars($student['first_name']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="lastName" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="lastName" name="lastName" value="<?php echo htmlspecialchars($student['last_name']); ?>" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Student</button>
                </form>
            <?php else: ?>
                <div class="alert alert-danger">Student not found.</div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>