<?php
session_start();
require '../../data/db.php';
include '../../classes/User.php';
include '../../classes/Admin.php';

if (isset($_SESSION["user_id"]) && $_SERVER["REQUEST_METHOD"] === "POST") {
    $id = $_SESSION["user_id"];
    $password = $_POST["new_password"];
    $update = User::UpdatePassword($conn, $password, $id);
    $delete = Admin::DeleteTempPassword($conn, $id);

    if ($update && $delete) {
        $msg = "Password Updated!";
        header("Location: ../index.php?message=" . urlencode($msg));
        exit();
    } else {
        $msg = "Oops something went wrong, please try again";
        header("Location: ../index.php?message=" . urlencode($msg));
        exit();
    }
}
?>