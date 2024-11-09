<?php
include 'db_connection.php';

// Delete functionality
if (isset($_GET['del'])) {
    $idToDelete = intval($_GET['del']);
    $deleteStmt = $conn->prepare("DELETE FROM entered_info WHERE id = ?");

    if ($deleteStmt === false) {
        die(json_encode(["status" => "error", "message" => $conn->error]));
    }

    $deleteStmt->bind_param("i", $idToDelete);
    if ($deleteStmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Record deleted successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => $deleteStmt->error]);
    }

    $deleteStmt->close();
    exit;
}

// Edit functionality
if (isset($_GET['editId'])) {
    $editId = intval($_GET['editId']);
    $stmt = $conn->prepare("SELECT username, gmail, mobile_no, password, gender, image FROM entered_info WHERE id = ?");
    $stmt->bind_param("i", $editId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode($row);
    } else {
        echo json_encode(["status" => "error", "message" => "No record found"]);
    }
    $stmt->close();
    exit;
}

// Check if the action is to remove the image
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['action'] == 'removeImage') {
    $userId = $_POST['userId'];
    $defaultImage = $_POST['defaultImage'];

    // Update the user's image in the database
    $sql = "UPDATE entered_info SET image = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $defaultImage, $userId);

    if ($stmt->execute()) {
        // Return a success response
        echo json_encode(['status' => 'success']);
    } else {
        // Return an error response
        echo json_encode(['status' => 'error', 'message' => 'Failed to update the image.']);
    }
    $stmt->close();
    exit;
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['action'])) {
    // Sanitize and validate input
    $id = isset($_POST['id']) ? intval($_POST['id']) : null;
    $username = htmlspecialchars($_POST['username']);
    $gmail = htmlspecialchars($_POST['gmail']);
    $mobile_no = htmlspecialchars($_POST['mobile_no']);
    $password = htmlspecialchars($_POST['password']);
    $gender = htmlspecialchars($_POST['gender']);
    $imagePath = 'default/defaultImg.png'; // Set default image path

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $targetDir = "uploads/";
        $targetFile = $targetDir . basename($_FILES["image"]["name"]);

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
            $imagePath = $targetFile;
        } else {
            echo json_encode(["status" => "error", "message" => "Error uploading image."]);
            exit;
        }
    }

    if ($conn->connect_error) {
        die(json_encode(["status" => "error", "message" => "Connection failed: " . $conn->connect_error]));
    }

    if (!empty($id)) {
        // Update query
        $stmt = $conn->prepare("UPDATE entered_info SET username=?, gmail=?, mobile_no=?, password=?, gender=?, image=? WHERE id=?");
        $stmt->bind_param("ssssssi", $username, $gmail, $mobile_no, $password, $gender, $imagePath, $id);
    } else {
        // Insert query
        $stmt = $conn->prepare("INSERT INTO entered_info (username, gmail, mobile_no, password, gender, image) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $username, $gmail, $mobile_no, $password, $gender, $imagePath);
    }

    if ($stmt === false) {
        die(json_encode(["status" => "error", "message" => $conn->error]));
    }

    // Execute and return response
    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Record saved successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => $stmt->error]);
    }

    $stmt->close();
    exit;
}

// Fetch all data functionality
if (isset($_GET['fetch'])) {
    $result = $conn->query("SELECT id, username, gmail, mobile_no, password, gender, image FROM entered_info");
    $data = [];

    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    echo json_encode($data);
    exit;
}

$conn->close();
