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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $age = $_POST['age'];
    $email = $_POST['email'];

    // Basic server-side validation
    if (!empty($first_name) && filter_var($email, FILTER_VALIDATE_EMAIL) && is_numeric($age)) {
        // Check if email already exists
        $checkEmailSql = "SELECT * FROM students WHERE email = '$email'";
        $result = $conn->query($checkEmailSql);

        if ($result->num_rows > 0) {
            echo "<div class='alert alert-danger mt-3'>Email already exists. Please use a different email.</div>";
        } else {
            // Insert the new student record
            $sql = "INSERT INTO students (first_name, last_name, age, email) VALUES ('$first_name', '$last_name', '$age', '$email')";

            if ($conn->query($sql) === TRUE) {
                echo "<div class='alert alert-success mt-3'>New record created successfully</div>";
            } else {
                echo "<div class='alert alert-danger mt-3'>Error: " . $sql . "<br>" . $conn->error . "</div>";
            }
        }
    } else {
        echo "<div class='alert alert-danger mt-3'>Invalid input. Please check your form data.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h2 class="text-center mb-4">Student Registration</h2>
        <form id="registrationForm" method="post" action="register.php" class="border p-4 bg-white rounded shadow-sm" onsubmit="return validateForm()">
            <div class="mb-3">
                <label for="first_name" class="form-label">First Name:</label>
                <input type="text" name="first_name" id="first_name" class="form-control" required>
                <div class="invalid-feedback">Please enter a valid first name.</div>
            </div>
            <div class="mb-3">
                <label for="last_name" class="form-label">Last Name:</label>
                <input type="text" name="last_name" id="last_name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="age" class="form-label">Age:</label>
                <input type="number" name="age" id="age" class="form-control" required>
                <div class="invalid-feedback">Please enter a valid age.</div>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" name="email" id="email" class="form-control" required>
                <div class="invalid-feedback">Please enter a valid email address.</div>
            </div>
            <div class="text-center">
                <input type="submit" value="Register" class="btn btn-primary">
            </div>
        </form>
        <div class="text-center mt-3">
            <a href="students.php" class="btn btn-secondary">View Registered Students</a>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <!-- JavaScript Validation -->
    <script>
        function validateForm() {
            let firstName = document.getElementById("first_name").value.trim();
            let age = document.getElementById("age").value.trim();
            let email = document.getElementById("email").value.trim();
            let emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; // Basic email pattern

            let valid = true;

            // Validate First Name
            if (firstName === "") {
                document.getElementById("first_name").classList.add("is-invalid");
                valid = false;
            } else {
                document.getElementById("first_name").classList.remove("is-invalid");
            }

            // Validate Age (must be a number)
            if (age === "" || isNaN(age)) {
                document.getElementById("age").classList.add("is-invalid");
                valid = false;
            } else {
                document.getElementById("age").classList.remove("is-invalid");
            }

            // Validate Email
            if (!emailPattern.test(email)) {
                document.getElementById("email").classList.add("is-invalid");
                valid = false;
            } else {
                document.getElementById("email").classList.remove("is-invalid");
            }

            return valid;
        }
    </script>
</body>
</html>
