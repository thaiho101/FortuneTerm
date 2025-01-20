<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if(!isset($_SESSION['authenticated']) || !$_SESSION['authenticated'])
{
    http_response_code(401); // Unauthorized
    exit;
}

header("Content-Type: application/json");
require_once("config.php");

$userId = $_SESSION['user_id'];
// Kết nối cơ sở dữ liệu
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Database connection failed: " . $conn->connect_error]);
    exit;
}

// Kiểm tra hành động
$action = $_POST['action'] ?? null;
if (!$action) {
    echo json_encode(["success" => false, "message" => "No action specified."]);
    exit;
}

if ($action === 'edit') {
    // Xử lý cập nhật
    if (isset($_POST['id'], $_POST['date'], $_POST['market'], $_POST['fbCost'], $_POST['otherCost'])) {
        $id = $_POST['id'];
        $date = $_POST['date'];
        $market = $_POST['market'];
        $fbCost = $_POST['fbCost'];
        $otherCost = $_POST['otherCost'];

        $updateQuery = "UPDATE market_cost SET visit_date = ?, market_name = ?, food_bev_cost = ?, other_cost = ? 
                        WHERE market_cost_id = ?
                        AND user_id = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("ssdsii", $date, $market, $fbCost, $otherCost, $id, $userId);

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Record updated successfully."]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to update record: " . $stmt->error]);
        }
        $stmt->close();
    } else {
        echo json_encode(["success" => false, "message" => "Missing required fields for edit."]);
    }
} elseif ($action === 'delete') {
    // Xử lý xóa
    if (isset($_POST['id'])) {
        $id = $_POST['id'];

        $deleteQuery = "UPDATE market_cost SET deleted = 'Y' 
                        WHERE market_cost_id = ?
                        AND user_id = ?";
        $stmt = $conn->prepare($deleteQuery);
        $stmt->bind_param("ii", $id, $userId);

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Record deleted successfully."]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to delete record: " . $stmt->error]);
        }
        $stmt->close();
    } else {
        echo json_encode(["success" => false, "message" => "Missing required fields for delete."]);
    }
} 

elseif ($action === 'setBudget') {
    if (isset($_POST['year'], $_POST['month'], $_POST['amount'])) {
        // $userId = $_SESSION['user_id'];
        $budgetYear = $_POST['year'];
        $budgetMonth = $_POST['month'];
        $budgetAmount = $_POST['amount'];

        // Check if the amount is valid
        if ($budgetAmount === "" || !is_numeric($budgetAmount)) {
            echo json_encode(["success" => false, "message" => "Invalid amount provided."]);
            exit;
        }

        // Check if the record already exists
        $checkQuery = "SELECT * FROM budget WHERE user_id = ? AND year = ? AND month = ?";
        $stmt = $conn->prepare($checkQuery);
        $stmt->bind_param("iss", $userId, $budgetYear, $budgetMonth);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Update existing record
            $updateQuery = "UPDATE budget SET amount = ? WHERE user_id = ? AND year = ? AND month = ?";
            $stmt = $conn->prepare($updateQuery);
            $stmt->bind_param("diss", $budgetAmount, $userId, $budgetYear, $budgetMonth);
            if ($stmt->execute()) {
                echo json_encode(["success" => true, "message" => "Budget updated successfully."]);
            } else {
                echo json_encode(["success" => false, "message" => "Failed to update budget: " . $stmt->error]);
            }
        } else {
            // Insert new record
            $insertQuery = "INSERT INTO budget (user_id, year, month, amount) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($insertQuery);
            $stmt->bind_param("issi", $userId, $budgetYear, $budgetMonth, $budgetAmount);
            if ($stmt->execute()) {
                echo json_encode(["success" => true, "message" => "Record added successfully."]);
            } else {
                echo json_encode(["success" => false, "message" => "Failed to add record: " . $stmt->error]);
            }
        }
        $stmt->close();
    } else {
        echo json_encode(["success" => false, "message" => "Missing required fields for setBudget."]);
    }
} 

else {
    echo json_encode(["success" => false, "message" => "Invalid action."]);
}

$conn->close();
?>
