<?php
session_start();
if (isset($_GET["message"])) {
    $message = json_encode($_GET["message"]);
   echo "<script>alert($message); window.location.href = 'login.php';</script>";
} 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NBCC Practicum Tool - Login</title>
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
        <h1>Login</h1>
        <form id="login_form" name="login_form" method="post" action="process/login_proc.php">
            <input type="text" id="email" name="email" placeholder="Email" />
            <span class="error-message" id="error_email"></span>
            <div class="password-wrapper">
                <input type="password" id="password" name="password" placeholder="Password" />
                <svg id="toggle_password" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="black">
                    <path id="eye_1" d="M12 4C7.03 4 2.73 7.5.68 12c2.05 4.5 6.35 8 11.32 8s9.27-3.5 11.32-8c-2.05-4.5-6.35-8-11.32-8zm0 14c-3.87 0-7.16-2.52-8.5-6 1.34-3.48 4.63-6 8.5-6s7.16 2.52 8.5 6c-1.34 3.48-4.63 6-8.5 6zm0-10c-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4z"/>
                </svg>
            </div>
            <span class="error-message" id="error_password"></span>
            <input class="btn-blue" type="submit" value="Login">
        </form> 
        <a href="signup.php"><button class="btn-aqua full-width">Create a new account</button></a>
    </div>
    <script src="resources/jquery-3.7.1.min.js"></script>
    <script src="resources/validate_login.js"></script>
    <script src="resources/show_password.js"></script>
    <script src="resources/hover.js"></script>
</body>
</html>