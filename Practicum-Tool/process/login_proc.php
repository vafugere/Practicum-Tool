<?php
session_start();
require '../data/db.php';
include '../classes/User.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];
    User::AuthenticateUser($conn, $email, $password);
}
?>