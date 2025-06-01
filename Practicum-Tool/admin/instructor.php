<?php
session_start();
require 'includes/admin_init.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: ../login.php");
    exit();
} else {
    $id = $_SESSION["user_id"];
    $temp = Admin::MatchTemp($conn, $id);
}
if (isset($_SESSION["account"])) {
    switch ($_SESSION["account"]) {
        case 1:
            header("Location: ../index.php");
            exit();
        case 2:
            break;
        case 3:
            header("Location: admin.php");
            exit();
        default:
            header("Location: ../login.php");
            exit();
    }
}
if (isset($_GET["message"])) {
    $message = json_encode($_GET["message"]);
   echo "<script>alert($message); window.location.href = 'admin.php';</script>";
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
           
            <div class="box-link-container">
                <a href="due_date.php" class="box-link">
                    <span>Set Due Date</span>
                    <img src="../images/links/clock.png" class="link-img" alt="Clock Icon" width="100px" height="100px">
                </a>
                <a href="upload.php" class="box-link">
                    <span>Upload List</span>
                    <img src="../images/links/upload.png" class="link-img" alt="Upload Icon" width="100px" height="100px">
                </a>
                <a href="list.php" class="box-link" id="instructor_list">
                    <span>View List</span>
                    <img src="../images/links/list.png" class="link-img" alt="List Icon" width="100px" height="100px">
                </a>
            </div>

        </div>
    </main>
    <?php include_once("includes/footer.php"); ?>
    <script src="resources/validate_temp.js"></script>
    <script src="../resources/show_password.js"></script>
    <script>
        const passwordForm = `
            <div id="overlay">
                <div class="form-container">
                    <div class="close-container"><a href="#" id="close" class="btn-close">X</a></div>
                    <p class="subtitle-black center-text">Change Password</p>
                    <form id="password_form" name="password_form" method="post" action="process/temp_proc.php">
                        <div class="password-wrapper">
                            <input type="password" id="password" name="password" placeholder="Temporary Password">
                            <svg id="toggle_password" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="black">
                                <path id="eye_1" d="M12 4C7.03 4 2.73 7.5.68 12c2.05 4.5 6.35 8 11.32 8s9.27-3.5 11.32-8c-2.05-4.5-6.35-8-11.32-8zm0 14c-3.87 0-7.16-2.52-8.5-6 1.34-3.48 4.63-6 8.5-6s7.16 2.52 8.5 6c-1.34 3.48-4.63 6-8.5 6zm0-10c-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4z"/>
                            </svg>
                        </div>
                        <span class="error-message" id="error_password"></span>
                        <div class="password-wrapper">
                            <input type="password" id="new_password" name="new_password" placeholder="New Password">
                            <svg id="toggle_new_password" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="black">
                                <path id="eye_3" d="M12 4C7.03 4 2.73 7.5.68 12c2.05 4.5 6.35 8 11.32 8s9.27-3.5 11.32-8c-2.05-4.5-6.35-8-11.32-8zm0 14c-3.87 0-7.16-2.52-8.5-6 1.34-3.48 4.63-6 8.5-6s7.16 2.52 8.5 6c-1.34 3.48-4.63 6-8.5 6zm0-10c-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4z"/>
                            </svg>
                        </div>
                        <span class="error-message" id="error_new_password"></span>
                        <div class="password-wrapper">
                            <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password">
                            <svg id="toggle_confirm_password" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="black">
                                <path id="eye_2" d="M12 4C7.03 4 2.73 7.5.68 12c2.05 4.5 6.35 8 11.32 8s9.27-3.5 11.32-8c-2.05-4.5-6.35-8-11.32-8zm0 14c-3.87 0-7.16-2.52-8.5-6 1.34-3.48 4.63-6 8.5-6s7.16 2.52 8.5 6c-1.34 3.48-4.63 6-8.5 6zm0-10c-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4z"/>
                            </svg>
                        </div>
                        <span class="error-message" id="error_confirm_password"></span>
                        <input type="submit" class="btn-blue" value="Save">
                    </form>
                </div>
            </div>`;

        $(document).ready(function() {
            <?php if ($temp === true): ?>
                $("body").append(passwordForm);
                initializeTempValidation();
            <?php endif; ?>

            $(document).on("click", "#close", function(e) {
                e.preventDefault();
                $("#overlay").remove();
            });
        });
    </script>
</body>
</html>