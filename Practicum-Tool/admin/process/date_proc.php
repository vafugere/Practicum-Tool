<?php
require '../../data/db.php';
include '../../classes/Admin.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $campus = $_POST["campus"];
    $date = $_POST["date"];
    $time = $_POST["time"];
    $datetime = $date . ' ' . $time;
    Admin::UpdateDueDate($conn, $campus, $datetime);
    header("Location: ../due_date.php");
    exit();
}
?>