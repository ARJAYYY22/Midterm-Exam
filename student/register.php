<?php
session_start();

header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

if (!isset($_SESSION['user_email'])) {
    header("Location: index.php");
    exit;
}

if (!isset($_SESSION['students'])) {
    $_SESSION['students'] = [];
}

$message = "";

function registerStudent($studentData) {
    global $message;
    $errors = [];

    if (empty($studentData['studentId'])) $errors[] = "Student ID cannot be empty.";
    if (empty($studentData['firstName'])) $errors[] = "First name cannot be empty.";
    if (empty($studentData['lastName'])) $errors[] = "Last name cannot be empty.";

    foreach ($_SESSION['students'] as $student) {
        if ($student['id'] === $studentData['studentId']) {
            $errors[] = "A student with this ID already exists.";
            break;
        }
    }

    if ($errors) {
        $message = implode("<br>", $errors);
    } else {
        $_SESSION['students'][] = [
            'id' => htmlspecialchars($studentData['studentId']),
            'first_name' => htmlspecialchars($studentData['firstName']),
            'last_name' => htmlspecialchars($studentData['lastName'])
        ];
        $message = "Student registered successfully!";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['studentId'])) {
    $studentData = [
        'studentId' => $_POST['studentId'],
        'firstName' => $_POST['firstName'],
        'lastName' => $_POST['lastName']
    ];
    registerStudent($studentData);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deleteId'])) {
    $deleteId = $_POST['deleteId'];
    $_SESSION['students'] = array_filter($_SESSION['students'], function($student) use ($deleteId) {
        return $student['id'] !== $deleteId;
    });
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register a New Student</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #7CFC00; }
        .breadcrumb { background-color: #F5FFFA; }
        .card { margin-top: 20px; }
        .card-header { background-color: #F5FFFA; }
        .table { margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2>Register a New Student</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="../dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Register Student</li>
            </ol>
        </nav>

        <?php if (!empty($message)): ?>
            <div class="alert <?php echo (strpos($message, 'successfully') !== false) ? 'alert-info' : 'alert-danger'; ?> alert-dismissible fade show" role="alert">
                <?php echo $message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="card">
            <div class="card-body">
                <form action="" method="POST">
                    <div class="mb-3">
                        <label for="studentId" class="form-label">Student ID</label>
                        <input type="text" class="form-control" id="studentId" name="studentId" placeholder="Enter Student ID" required>
                    </div>
                    <div class="mb-3">
                        <label for="firstName" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="firstName" name="firstName" placeholder="Enter First Name" required>
                    </div>
                    <div class="mb-3">
                        <label for="lastName" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="lastName" name="lastName" placeholder="Enter Last Name" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Student</button>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">Student List</div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">Student ID</th>
                            <th scope="col">First Name</th>
                            <th scope="col">Last Name</th>
                            <th scope="col">Options</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($_SESSION['students'])): ?>
                            <?php foreach ($_SESSION['students'] as $student): ?>
                                <tr>
                                    <td><?php echo $student['id']; ?></td>
                                    <td><?php echo $student['first_name']; ?></td>
                                    <td><?php echo $student['last_name']; ?></td>
                                    <td>
                                        <form action="edit.php" method="GET" style="display:inline;">
                                            <button type="submit" class="btn btn-success btn-sm" name="studentId" value="<?php echo $student['id']; ?>">Edit</button>
                                        </form>
                                        <form action="delete.php" method="GET" class="d-inline">
                                            <input type="hidden" name="studentId" value="<?php echo $student['id']; ?>">   
                                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center">No student records found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
