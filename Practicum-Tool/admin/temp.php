<?php
session_start();
require 'includes/admin_init.php';

if (!isset($_SESSION["user_id"])) {
    header("location:login.php");
    exit();
}
if (isset($_GET["message"])) {
    $message = json_encode($_GET["message"]);
   echo "<script>alert($message); window.location.href = 'temp.php';</script>";
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
            
            <div class="frame w-500 flex-center">
                <h4>Temporary Password</h4>
                <span class="temp-text">
                    <?= $_SESSION["temp_pw"]; ?>
                </span>
                <p class="med-text">
                    account: <?= $_SESSION["email"]; ?>
                </p>
            </div>

        </div>
    </main>
    <?php include_once("includes/footer.php"); ?>
</body>
</html>