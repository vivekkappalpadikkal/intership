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

// Handle delete request
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $sql = "DELETE FROM students WHERE id = $delete_id";

    if ($conn->query($sql) === TRUE) {
        echo "<div class='alert alert-success'>Record deleted successfully</div>";
    } else {
        echo "<div class='alert alert-danger'>Error deleting record: " . $conn->error . "</div>";
    }
}

// Fetch students
$sql = "SELECT * FROM students";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registered Students</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container mt-5">
        <h2 class="mb-4 text-center">Actived Students</h2>
        
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>

                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Age</th>
                        <th>Email</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    
                                    <td>{$row['first_name']}</td>
                                    <td>{$row['last_name']}</td>
                                    <td>{$row['age']}</td>
                                    <td>{$row['email']}</td>
                                    <td><a href='students.php?delete_id={$row['id']}' class='btn btn-danger btn-sm' onclick='return confirmDelete()'>Delete</a>
                                    <a href='edit.php?id={$row['id']}' class='btn btn-warning btn-sm'>Edit</a></td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6' class='text-center'>No students found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <a href="register.php" class="btn btn-primary mt-3">Register a New Student</a>
        
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Function to show confirmation dialog
        function confirmDelete() {
            return confirm('Are you sure you want to delete this record?');
        }
    </script>
</body>
</html>
