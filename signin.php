<?php
include 'db_connection.php'; 

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $gmail = $_POST['gmail'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM entered_info WHERE gmail = ? AND password = ?");
    $stmt->bind_param("ss", $gmail, $password); 
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Successful login
        $row = $result->fetch_assoc(); 
        
        session_start();
        $_SESSION['username'] = $row['username'];

        header("Location: welcome.php");
        exit();
    } else {
        $error = "Invalid Gmail or password.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container">
    <h2 class="mt-5">Sign In</h2>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    <form action="" method="POST">
        <div class="form-group mt-3">
            <label for="gmail">Email:</label>
            <input type="text" class="form-control" name="gmail" required>
        </div>
        <div class="form-group mt-3">
            <label for="password">Password:</label>
            <input type="password" class="form-control" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary mt-4">Sign In</button>
        <div class="mt-2"><a href="forget_password.php">Forget your password</a></div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
