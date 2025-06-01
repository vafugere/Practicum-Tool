<?php
session_start();
require '../../data/db.php';
header('Content-Type: application/json');

$response = ["available" => false];

if (isset($_SESSION["user_id"])) {
    if (isset($_GET["temp"])) {
        $id = $_SESSION["user_id"];
        $temp = $_GET["temp"];

        $stmt = $conn->prepare('CALL MatchTemp(?)');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->bind_result($dbTemp);
        $stmt->fetch();
        if (password_verify($temp, $dbTemp)) {
            $response["available"] = true;
        }

        $stmt->close();
        $conn->next_result();
    }
}
echo json_encode($response);
$conn->close();
?>