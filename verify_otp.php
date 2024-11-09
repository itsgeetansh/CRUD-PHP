<?php
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $gmail = $_POST['gmail'];
    $otp = $_POST['otp'];

    $stmt = $conn->prepare("SELECT * FROM entered_info WHERE gmail = ? AND otp = ?");
    $stmt->bind_param("ss", $gmail, $otp);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc(); 
        
        session_start();
        $_SESSION['username'] = $row['username'];

        $clearOtpStmt = $conn->prepare("UPDATE entered_info SET otp = NULL WHERE gmail = ?");
        $clearOtpStmt->bind_param("s", $gmail);
        $clearOtpStmt->execute();

        header("Location: welcome.php");
        exit();
    } else {
        echo "Invalid OTP.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container">
    <h2 class="mt-5">Verify OTP</h2>
    <form action="" method="POST">
        <input type="hidden" name="gmail" value="<?php echo $_GET['gmail']; ?>">
        <div class="form-group mt-3">
            <label for="otp">Enter OTP:</label>
            <input type="text" class="form-control" name="otp" required>
        </div>
        <button type="submit" class="btn btn-primary mt-4">Verify OTP</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
