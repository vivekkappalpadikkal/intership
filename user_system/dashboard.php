<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "user_system";
$conn = new mysqli($servername, $username, $password, $dbname);

// Fetch all users
$sql = "SELECT * FROM users";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <div class="container">
        <h2 class="my-4">Welcome to the Dashboard</h2>
        <h3>All Users:</h3>
        <ul class="list-group">
            <?php while ($row = $result->fetch_assoc()) { ?>
            <li class="list-group-item"><?php echo $row['name']; ?> - <?php echo $row['email']; ?></li>
            <?php } ?>
        </ul>
        <a href="profile.php" class="btn btn-success my-3">Profile</a>
    </div>
</body>
</html>
