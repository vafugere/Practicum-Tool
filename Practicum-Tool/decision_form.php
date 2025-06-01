<?php
session_start();
require 'includes/student_init.php';

if (!isset($_SESSION["user_id"])) {
    header("location:login.php");
    exit();
} else {
    $id = $_SESSION["user_id"];
}
if (isset($_SESSION["account"])) {
    switch ($_SESSION["account"]) {
        case 1:
            break;
        case 2:
        case 3:
            header("Location: admin/index.php");
            exit();
        default:
            header("Location: login.php");
            exit();
    }
}
if (isset($_GET["message"])) {
    $message = json_encode($_GET["message"]);
   echo "<script>alert($message); window.location.href = 'decision_form.php';</script>";
} 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NBCC Practicum Tool</title>
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <link rel="stylesheet" href="resources/style.css">
</head>
<body>
    <?php include_once("includes/header.php"); ?>
    <main>
        <div class="column narrow">
            
            <div class="frame w-800 flex-center">
                <p class="med-text">Do you want to opt into the practicum?</p>
                <div class="w-300">
                    <form id="decision_form" name="decision_form" method="post" action="process/decision_proc.php">
                        <select id="decision" name="decision" required>
                            <option value="2">Yes</option>
                            <option value="3">No</option>
                        </select>
                        <input type="submit" class="btn-blue" value="Submit">
                    </form>
                </div>
            </div>

        </div>
    </main>
    <?php include_once("includes/footer.php"); ?>
</body>
</html>