<?php
if (isset($_GET["message"])) {
    $message = json_encode($_GET["message"]);
   echo "<script>alert($message); window.location.href = 'signup.php';</script>";
} 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NBCC Practicum Tool - Sign up</title>
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <link rel="stylesheet" href="resources/style.css">
</head>
<body>
    <header>
        <div class="small-header">
        <a href="index.php" id="home_link"><img src="images/icons/home.png" id="home_img" width="28px" height="28px" alt="Home Icon" class="dots"></a>
        <a href="https://www.nbcc.ca" target="_blank">
            <img src="images/NBCCLogo.png" height="30px" class="dots" alt="NBCC Logo">
        </a>
        <div class="header-title">Practicum Tool</div>
        </div>
        <hr>
    </header>
    <div class="form-container">
        <h2>Create your account</h2>
        <form id="signup_form" name="signup_form" method="post" action="process/signup_proc.php">
            <input type="text" id="fname" name="fname" placeholder="First Name" required>
            <span class="error-message" id="error_fname"></span>
            <input type="text" id="lname" name="lname" placeholder="Last Name" required>
            <span class="error-message" id="error_lname"></span>
            <input type="text" id="email" name="email" placeholder="Email">
            <span class="error-message" id="error_email"></span>
            <input type="text" id="confirm_email" name="confirm_email" placeholder="Confirm Email" required>
            <span class="error-message" id="error_confirm_email"></span>
            <input type="text" id="org_id" name="org_id" placeholder="Student ID Number" required>
            <span class="error-message" id="error_org_id"></span>
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
            <input class="btn-blue" type="submit" value="Create">
        </form> 
        <a href="login.php"><button class="btn-aqua full-width">Already have an account?</button></a>
    </div>
    <script src="resources/jquery-3.7.1.min.js"></script>
    <script src="resources/email_availability.js"></script>
    <script src="resources/validate_signup.js"></script>
    <script src="resources/show_password.js"></script>
    <script src="resources/hover.js"></script>
</body>
</html>