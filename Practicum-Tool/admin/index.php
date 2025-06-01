<?php
session_start();
if (isset($_SESSION["account"])) {
    switch ($_SESSION["account"]) {
        case 1:
            header("Location: ../index.php");
            exit();
        case 2:
            header("Location: instructor.php");
            exit();
        case 3:
            header("Location: admin.php");
            exit();
        default:
            header("Location: ../login.php");
            exit();
    }
} else {
    header("Location: ../login.php");
    exit();
}
?>