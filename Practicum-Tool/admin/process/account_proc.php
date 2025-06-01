<?php
session_start();
require '../../data/db.php';
include '../../classes/Admin.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $fname = $_POST["fname"];
    $lname = $_POST["lname"];
    $email = $_POST["email"];
    $account = $_POST["account"];
    $mainCampus = $_POST["main_campus"];
    $campus = $_POST["campus"];

    $temp_password = Admin::TempPassword();
    $_SESSION["email"] = $_POST["email"];
    $_SESSION["temp_pw"] = $temp_password;
    $password = password_hash($temp_password, PASSWORD_DEFAULT);

    $admin = new Admin(null, $fname, $lname, $email, $password, $account, null, [$mainCampus]);
    foreach ($campus as $campusId ) {
        $admin->addCampus($campusId);
    }
    Admin::CreateAdmin($conn, $admin);
}
?>