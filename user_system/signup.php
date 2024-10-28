<?php
// Connect to MySQL
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "user_system";
$conn = new mysqli($servername, $username, $password, $dbname);

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Basic validation
    if (empty($name) || empty($email) || empty($password)) {
        $errors[] = "All fields are required.";
    } else {
        // Check if email already exists
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $errors[] = "Email already exists.";
        } else {
            // Encrypt the password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Insert user into database
            $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $name, $email, $hashedPassword);
            if ($stmt->execute()) {
                header("Location: signin.php");
                exit();
            } else {
                $errors[] = "Something went wrong. Please try again.";
            }
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <div class="container">
        <h2 class="my-4">Signup</h2>

        <?php if (!empty($errors)) { ?>
        <div class="alert alert-danger">
            <ul>
                <?php foreach ($errors as $error) { echo "<li>$error</li>"; } ?>
            </ul>
        </div>
        <?php } ?>

        <form action="signup.php" method="POST" class="border p-4 bg-white">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" class="form-control" placeholder="Enter your name">
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" class="form-control" placeholder="Enter your email">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="Enter your password">
            </div>
            <button type="submit" class="btn btn-primary">Sign up</button>
            <a href="signin.php" class="text-center btn btn-secondary">If you already Signup, sign in</a>
        </form>
    </div>
</body>
</html>
