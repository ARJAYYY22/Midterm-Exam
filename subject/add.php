<?php
$pageTitle = "Add Subject";
$cssfile = "../css/addsub.css";
include("../header.php");

// Include the functions file
include("../functions.php");

if (!isset($_SESSION['user_email'])) {
    // Redirect to login page if not logged in
    header("Location: index.php");
    exit;
}// T

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/addsub.css">
    <title>Document</title>
</head>
<body></body>
    

<!-- HTML for the form and subject list -->
<div class="container mt-5">
    <h2 class="text-center">Add a New Subject</h2>
    <nav aria-label="breadcrumb" >
        <ol class="breadcrumb" >
            <li class="breadcrumb-item"><a href="../dashboard.php">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Add Subject</li>
        </ol>
    </nav>
    <?php
// Display the error message if there is one
if (!empty($error_message)) {
    echo $error_message;
}
?>
    <div class="form-container mb-4">
        <form method="POST">
            <div class="mb-3">
                <label for="subjectCode" class="form-label">Subject Code</label>
                <input type="text" class="form-control" id="subjectCode" name="subjectCode" placeholder="Enter Subject Code">
            </div>
            <div class="mb-3">
                <label for="subjectName" class="form-label">Subject Name</label>
                <input type="text" class="form-control" id="subjectName" name="subjectName" placeholder="Enter Subject Name">
            </div>
            <button type="submit" class="btn btn-primary">Add Subject</button>
        </form>
    </div>

    <div class="table-container">
        <h4>Subject List</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Subject Code</th>
                    <th>Subject Name</th>
                    <th>Option</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($_SESSION['subjects'])): ?>
                    <?php foreach ($_SESSION['subjects'] as $subject): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($subject['code']); ?></td>
                            <td><?php echo htmlspecialchars($subject['name']); ?></td>
                            <td><a href="edit.php"><button class="btn btn-success">Edit</button></a>   <a href="delete.php?code=<?php echo urlencode($subject['code']); ?>"><button class="btn btn-danger">Delete</button></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" class="text-center">No subjects found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
include("../footer.php");
?>
