<?php
session_start();

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

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register a New Student</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .breadcrumb { background-color: #e9ecef; }
        .card { margin-top: 20px; }
        .card-header { background-color: #f8f9fa; }
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

        <!-- Alert Message -->
        <?php if (!empty($message)): ?>
            <div class="alert <?php echo (strpos($message, 'successfully') !== false) ? 'alert-info' : 'alert-danger'; ?> alert-dismissible fade show" role="alert">
                <?php echo $message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Registration Form -->
        <div class="card">
            <div class="card-body">
                <form action="" method="POST">
                    <div class="mb-3">
                        <label for="studentId" class="form-label">Student ID</label>
                        <input type="text" class="form-control" id="studentId" name="studentId" placeholder="Enter Student ID">
                    </div>
                    <div class="mb-3">
                        <label for="firstName" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="firstName" name="firstName" placeholder="Enter First Name">
                    </div>
                    <div class="mb-3">
                        <label for="lastName" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="lastName" name="lastName" placeholder="Enter Last Name">
                    </div>
                    <button type="submit" class="btn btn-primary">Add Student</button>
                </form>
            </div>
        </div>

        <!-- Student List -->
        <div class="card">
            <div class="card-header">Student List</div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">Student ID</th>
                            <th scope="col">First Name</th>
                            <th scope="col">Last Name</th>
                            <th scope="col">Option</th>
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
                                        <form action="" method="POST" style="display:inline;">
                                            <!-- <input type="hidden" name="deleteId" value="<?php echo $student['id']; ?>"> -->
                                            <button type="submit" class="btn btn-success btn-sm">Edit</button> <button type="submit" class="btn btn-danger btn-sm">Delete</button>
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

    <?php
    // Handle student deletion
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deleteId'])) {
        $deleteId = $_POST['deleteId'];
        $_SESSION['students'] = array_filter($_SESSION['students'], function($student) use ($deleteId) {
            return $student['id'] !== $deleteId;
        });
        header("Location: " . $_SERVER['PHP_SELF']); // Refresh page to clear POST data
        exit;
    }
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
