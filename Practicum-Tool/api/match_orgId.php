<?php
require '../data/db.php';
header('Content-Type: application/json');

$response = ["available" => false];

if (isset($_GET["id"])) {
    $id = $_GET["id"];

    $stmt = $conn->prepare('CALL MatchStudentId(?)');
    $stmt->bind_param('s', $id);

    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $response["available"] = true;
    }
    $stmt->close();
}
echo json_encode($response);
?>