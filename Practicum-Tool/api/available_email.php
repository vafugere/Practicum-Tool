<?php
require '../data/db.php';
header('Content-Type: application/json');

$response = ["available" => false];

if (isset($_GET["email"])) {
    $email = $_GET["email"];

    $stmt = $conn->prepare('CALL AvailableEmail(?)');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $response["available"] = true;
    }
    $stmt-> close();
}
echo json_encode($response);
?>