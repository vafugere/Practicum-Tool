<?php
session_start();
require 'includes/admin_init.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: ../login.php");
    exit();
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
   echo "<script>alert($message); window.location.href = 'list.php';</script>";
} 
$id = $_SESSION["user_id"];
$account = $_SESSION["account"];
$instructor = ($account == 2) ? $id : null;
$campusList = Admin::CampusList($conn, $id);
$currentYear = date('Y');
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 25;
$offset = ($page - 1) * $limit;
$total_students = Student::GetTotalStudents($conn, $instructor, null, null, $campusList, $currentYear, $limit);
$total_pages = ceil($total_students / $limit);
$students = Student::SelectStudents($conn, $instructor, null, null, null, $campusList, $currentYear, $limit, $offset);
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

            <h3>ITBA: Information Technology Business Analysis</h3>

            <div class="filter-controls">
                <?php Student::ViewStudentName() ?>
                <?php Student::ViewCampus($conn, $campusList); ?>
                <?php 
                    if ($account == 2) {
                        Student::ViewYear($conn, $id);
                    } else if ($account == 3) {
                        Student::ViewAdminYear($conn);
                    }
                 ?>
                <?php Student::ViewEligible(); ?>
                <?php Student::ViewForm($conn); ?>
                <?php Student::ViewAmount(); ?>
            </div>

            <form id="student_form" name="student_form" method="post" action="process/list_proc.php">
                <div id="display_students">
                    <?php Student::DisplayStudents($conn, $students); 
                        Student::DisplayPages($page, $total_pages); ?>
                </div>
                
                       
                <div class="list-buttons">
                    <a href="#" id="btn_print" class="btn-aqua w-200">Print</a>
                    <a href="#" id="btn_excel" class="btn-aqua w-200">Download Excel</a>
                    <input type="submit" class="btn-blue w-200" value="Save">
                </div>
            </form>
             
        </div>
    </main>

    <?php include_once("includes/footer.php"); ?>
    <script src="resources/filter_students.js"></script>
    <script src="resources/export_print.js"></script>
</body>
</html>