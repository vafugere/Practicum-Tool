<?php
require __DIR__ . "/../config.php";

global $conn;
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if (!$conn) {
    die('Could not connect: ' . mysqli_error());
}
?>