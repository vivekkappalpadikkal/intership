<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "user_system";
$conn = new mysqli($servername, $username, $password, $dbname);

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Basic validation
    if (empty($email) || empty($password)) {
        $errors[] = "Both fields are required.";
    } else {
        // Check if user exists
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($password, $user['password'])) {
            // Password is correct
            $_SESSION['user_id'] = $user['id'];
            header("Location: dashboard.php");
            exit();
        } else {
            $errors[] = "Invalid email or password.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signin</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <div class="container">
        <h2 class="my-4">Signin</h2>

        <?php if (!empty($errors)) { ?>
        <div class="alert alert-danger">
            <ul>
                <?php foreach ($errors as $error) { echo "<li>$error</li>"; } ?>
            </ul>
        </div>
        <?php } ?>

        <form action="signin.php" method="POST" class="border p-4 bg-white">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" class="form-control" placeholder="Enter your email">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="Enter your password">
            </div>
            <button type="submit" class="btn btn-primary">Sign in</button>
            <a href="signup.php" class="text-center btn btn-secondary">Sign Up</a>
        </form>
    </div>
</body>
</html>
