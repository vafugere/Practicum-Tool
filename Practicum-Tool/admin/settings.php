<?php
session_start();
require 'includes/admin_init.php';

if (!isset($_SESSION["user_id"])) {
    header("location:login.php");
    exit();
} else {
    $id = $_SESSION["user_id"];
    $user = User::GetUserById($conn, $id);
}
if (isset($_GET["message"])) {
    $message = json_encode($_GET["message"]);
   echo "<script>alert($message); window.location.href = 'settings.php';</script>";
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
                <h4>Account Settings</h4>
                <div class="name-container">
                    <?php include("../includes/name_badge.php"); ?>
                </div>
                <form id="settings_form" name="settings_form" method="post" action="process/settings_proc.php">
                    <label class="settings-label">Name:</label>
                    <input type="text" id="fname" name="fname" placeholder="<?= $user->firstName; ?>" readonly>
                    <span class="error-message" id="error_fname"></span>
                    <input type="text" id="lname" name="lname" placeholder="<?= $user->lastName; ?>" readonly>
                    <span class="error-message" id="error_lname"></span>
                    <label class="settings-label">Email:</label>
                    <input type="text" id="email" name="email" placeholder="<?= $user->email; ?>" readonly>
                    <span class="error-message" id="error_email"></span>
                    <label class="settings-label">Password</label>
                    <div class="password-wrapper">
                        <input type="password" id="password" name="password" placeholder="New Password" />
                        <svg id="toggle_password" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="black">
                            <path id="eye_1" d="M12 4C7.03 4 2.73 7.5.68 12c2.05 4.5 6.35 8 11.32 8s9.27-3.5 11.32-8c-2.05-4.5-6.35-8-11.32-8zm0 14c-3.87 0-7.16-2.52-8.5-6 1.34-3.48 4.63-6 8.5-6s7.16 2.52 8.5 6c-1.34 3.48-4.63 6-8.5 6zm0-10c-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4z"/>
                        </svg>
                    </div>
                    <span class="error-message" id="error_password"></span>
                    <div class="password-wrapper">
                        <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" />
                        <svg id="toggle_confirm_password" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="black">
                            <path id="eye_2" d="M12 4C7.03 4 2.73 7.5.68 12c2.05 4.5 6.35 8 11.32 8s9.27-3.5 11.32-8c-2.05-4.5-6.35-8-11.32-8zm0 14c-3.87 0-7.16-2.52-8.5-6 1.34-3.48 4.63-6 8.5-6s7.16 2.52 8.5 6c-1.34 3.48-4.63 6-8.5 6zm0-10c-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4z"/>
                        </svg>
                    </div>
                    <span class="error-message" id="error_confirm_password"></span>
                    <input type="submit" class="btn-blue" value="Save">
                </form>
            </div>

        </div>
    </main>
    <?php include_once("includes/footer.php"); ?>
    <script src="../resources/validate_settings.js"></script>
    <script src="../resources/show_password.js"></script>
</body>
</html>