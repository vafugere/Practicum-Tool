<?php
session_start();
require 'includes/admin_init.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: ../login.php");
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
        case 3:
            break;
        default:
            header("Location: ../login.php");
            exit();
    }
}
if (isset($_GET["message"])) {
    $message = json_encode($_GET["message"]);
   echo "<script>alert($message); window.location.href = 'due_dates.php';</script>";
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

            <div class="frame w-600 flex-center">
                <h4>Set Due Date</h4>
                <form id="due_dates" name="due_dates" class="w-300" method="post" action="process/date_proc.php">
                    <?php Admin::SelectCampus($conn, $id); ?>
                    <input type="date" id="date" name="date" class="date-text" min="<?= date('Y-m-d') ?>" required>
                    <?php Admin::SelectTime(); ?>
                    <button class="btn-blue" type="submit">Save</button>
                </form>
            </div>

            <?php Admin::DisplayDates($conn, $id); ?>
            
        </div>
    </main>
    <?php include_once("includes/footer.php"); ?>
</body>
</html>