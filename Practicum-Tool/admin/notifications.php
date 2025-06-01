<?php
session_start();
require 'includes/admin_init.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: ../login.php");
    exit();
} else {
    $id = $_SESSION["user_id"];
}

if (isset($_GET["message"])) {
    $message = json_encode($_GET["message"]);
   echo "<script>alert($message); window.location.href = 'notifications.php';</script>";
} 
$page = isset($_GET["page"]) ? (int)$_GET["page"] : 1;
$limit = 8;
$offset = ($page - 1) * $limit;
$total_notif = Notification::CountNotifications($conn, $id);
$total_pages = ceil($total_notif / $limit);
$notifications = Notification::GetAllNotifications($conn, $id, $limit, $offset);
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

            <div class="title-blue w-800">
                <img src="../images/icons/notification.png" width="28px" height="28px" alt="Notification Icon">
                Notifications
            </div>
            <div class="box-blue w-800">
                <?php Notification::DisplayAllAdminNotifications($conn, $notifications); ?>
                <?php Notification::DisplayPages($page, $total_pages); ?>
            </div>
        
        </div>
    </main>
    <?php include_once("includes/footer.php"); ?>
</body>
</html>