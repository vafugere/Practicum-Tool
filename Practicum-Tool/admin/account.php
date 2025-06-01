<?php
session_start();
require 'includes/admin_init.php';

if (!isset($_SESSION["user_id"])) {
    header("location:login.php");
    exit();
} else {
    $id = $_SESSION["user_id"];
}
if (isset($_SESSION["account"])) {
    switch ($_SESSION["account"]) {
        case 1:
            header("Location: ../index.php");
            exit();
        case 2:
            header("Location: instructor.php");
            exit();
        case 3:
            break;
        default:
            header("Location: ../login.php");
            exit();
    }
}
if (isset($_GET["message"])) {
    $message = json_encode($_GET["message"]);
   echo "<script>alert($message); window.location.href = 'account.php';</script>";
} 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NBCC Practicum Tool</title>
    <link rel="icon" type="image/x-icon" href="../favicon.ico">
    <link rel="stylesheet" href="../resources/style.css">
</head>
<body>
    <?php include_once("includes/header.php"); ?>
    <main>
        <div class="column main">

            <div class="frame w-500">
                <h4>Create Account</h4>
                <form id="create_account" name="create_account" method="post" action="process/account_proc.php">
                    <input type="text" id="fname" name="fname" placeholder="First Name" required>
                    <span class="error-message" id="error_fname"></span>

                    <input type="text" id="lname" name="lname" placeholder="Last Name" required>
                    <span class="error-message" id="error_lname"></span>

                    <input type="text" id="email" name="email" placeholder="Email" required">
                    <span class="error-message" id="error_email"></span>

                    <input type="text" id="confirm_email" name="confirm_email" placeholder="Confirm Email" required>
                    <span class="error-message" id="error_confirm_email"></span>

                    <?php Admin::SelectAccountType($conn); ?>
                    <?php Admin::SelectMainCampus($conn); ?>
                    <?php Admin::CampusCheckbox($conn); ?>

                    <input type="submit" class="btn-blue" value="Create Account">
            </form>
            </div>
            
        </div>
    </main>
    <?php include_once("includes/footer.php"); ?>
    <script src="resources/email_availability.js"></script>
    <script src="resources/validate_account.js"></script>
</body>
</html>