<?php
session_start();
require '../data/db.php';
include '../classes/User.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $orgId = $_POST['org_id'];
    $password = $_POST['password'];
    $password = password_hash($password, PASSWORD_DEFAULT);
    $account = 1;

    $user = new User(null, $fname, $lname, $email, $orgId, $password, $account, null);

    $result = User::UpdateStudentEmail($conn, $user->email, $user->orgId);
    if ($result) {
        User::CreateUser($conn, $user);
    } else {
        $msg = "Failed to update student record, please try again";
        header("Location: ../signup.php?message=" . urlencode($msg));
    }
}
?>