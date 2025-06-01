<?php
session_start();
require '../../data/db.php';
include '../../classes/User.php';

if (isset($_SESSION["user_id"])) {
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $id = $_SESSION["user_id"];

        if (isset($_POST["password"]) && trim($_POST["password"]) !== "") {
            $password = trim($_POST["password"]);
            $updated = User::UpdatePassword($conn, $password, $id);
            if ($updated) {
                $msg = "New password saved!";
                header("Location: ../settings.php?message=" . urlencode($msg));
                exit();
            }
        }
    }
}
header("Location: ../settings.php");
exit();
?>
