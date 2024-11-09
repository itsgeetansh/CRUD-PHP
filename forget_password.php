<?php
include 'db_connection.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $gmail = $_POST['gmail'];

    $stmt = $conn->prepare("SELECT * FROM entered_info WHERE gmail = ?");
    $stmt->bind_param("s", $gmail);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $otp = rand(100000, 999999);

        $updateStmt = $conn->prepare("UPDATE entered_info SET otp = ? WHERE gmail = ?");
        $updateStmt->bind_param("ss", $otp, $gmail);
        $updateStmt->execute();

        if ($updateStmt->execute()) {
            echo "OTP updated successfully!";
        } else {
            echo "Failed" . $updateStmt->error;
        }

        $subject = "Your OTP for Password Reset";
        // echo $subject;
        $message = "Your OTP is: " . $otp;
        $headers = "From: me@example.com";
        $to = $gmail;

        
        

        if(mail($to, $subject, $message, $headers)) {
            echo "Mail sent successfully!";
        } else {
            echo "Failed to send mail.";
        }
        header("Location: verify_otp.php?gmail=" . $gmail);
        exit();
    } else {
        echo "Email not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container">
    <h2 class="mt-5">Forgot Password</h2>
    <form action="" method="POST">
        <div class="form-group mt-3">
            <label for="gmail">Email:</label>
            <input type="text" class="form-control" name="gmail" required>
        </div>
        <button type="submit" class="btn btn-primary mt-4">Send OTP</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
