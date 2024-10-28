<?php
$host = 'localhost';
$user = 'root';
$password = 'root';
$dbname = 'studentdb';

// Create connection
$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if student ID is provided in the URL
if (isset($_GET['id'])) {
    $student_id = $_GET['id'];

    // Fetch student data based on ID
    $sql = "SELECT * FROM students WHERE id = $student_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $student = $result->fetch_assoc();
    } else {
        echo "Student not found!";
        exit;
    }
}

// Update student record
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $age = $_POST['age'];
    $email = $_POST['email'];

    $sql = "UPDATE students SET first_name = '$first_name', last_name = '$last_name', age = '$age', email = '$email' WHERE id = $student_id";

    if ($conn->query($sql) === TRUE) {
        echo "<div class='alert alert-success'>Record updated successfully</div>";
        // After successful deletion, redirect to students.php
        header("Location: students.php");
        exit(); // Ensure no further code is executed after redirect
    } else {
        echo "<div class='alert alert-danger'>Error: " . $sql . "<br>" . $conn->error . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h2 class="text-center mb-4">Edit Student</h2>
        <form method="post" action="" class="border p-4 bg-white rounded shadow-sm">
            <div class="mb-3">
                <label for="first_name" class="form-label">First Name:</label>
                <input type="text" name="first_name" id="first_name" class="form-control" value="<?php echo $student['first_name']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="last_name" class="form-label">Last Name:</label>
                <input type="text" name="last_name" id="last_name" class="form-control" value="<?php echo $student['last_name']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="age" class="form-label">Age:</label>
                <input type="number" name="age" id="age" class="form-control" value="<?php echo $student['age']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" name="email" id="email" class="form-control" value="<?php echo $student['email']; ?>" required>
            </div>
            <div class="text-center">
                <input type="submit" value="Update" class="btn btn-success" onclick='return confirmUpdate()'>
            </div>
        </form>
    </div>
</body>
<script>
        // Function to show confirmation dialog
        function confirmUpdate() {
            return confirm('Are you sure you want to update this record?');
        }
    </script>
</html>
