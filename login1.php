<?php
session_start();
include("connection.php");
include("functions.php");

$response = array('status' => '', 'message' => '');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["ajax"]) && $_POST["ajax"] == 'true') {
    if (isset($_POST["Username_or_Email"]) && isset($_POST["Password"])) {
        $usernameOrEmail = sanitizeInput($_POST["Username_or_Email"]);
        $password = sanitizeInput($_POST["Password"]);

        $query = "SELECT User_ID, Username FROM users WHERE (Username = ? OR Email = ?) AND Password = ?";
        $stmt = $con->prepare($query);
        if ($stmt === false) {
            $response['status'] = 'error';
            $response['message'] = 'Database prepare error: ' . htmlspecialchars($con->error);
            echo json_encode($response);
            exit;
        }
        $stmt->bind_param("sss", $usernameOrEmail, $usernameOrEmail, $password);
        $stmt->execute();
        if ($stmt === false) {
            $response['status'] = 'error';
            $response['message'] = 'Database execute error: ' . htmlspecialchars($con->error);
            echo json_encode($response);
            exit;
        }
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($user_id, $username);
            $stmt->fetch();
            $_SESSION['User_ID'] = $user_id;
            $_SESSION['Username'] = $username;

            $response['status'] = 'success';
            $response['username'] = $username;
            echo json_encode($response);
            exit;
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Invalid username/email or password.';
            echo json_encode($response);
            exit;
        }
        $stmt->close();
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Please enter username/email and password!';
        echo json_encode($response);
        exit;
    }
}
$con->close();

function sanitizeInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}
?>
