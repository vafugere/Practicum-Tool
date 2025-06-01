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
   echo "<script>alert($message); window.location.href = 'upload.php';</script>";
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
           
            <div id="loading">
                <div class="spinner"></div>
            </div>

            <div class="frame w-500 flex-center">
                <h4>Upload Student List</h4>
                <form id="upload_file" name="upload_file" class="w-300" action="process/upload_proc.php" method="post" enctype="multipart/form-data">

                    <?php Admin::SelectCampus($conn, $id); ?>
                    <?php Admin::SelectYear(); ?>

                    <input type="file" name="excel_file" id="excel_file" accept=".xls,.xlsx" required hidden>
                    <label for="excel_file" class="btn-blue">Choose File</label>
                    <button class="btn-blue" type="submit">Upload</button>
                     <p class="center-text" id="file_name">No file chosen</p>
                </form>
            </div>
      
        </div>
    </main>
    <?php include_once("includes/footer.php"); ?>
    <script src="resources/upload.js"></script>
</body>
</html>