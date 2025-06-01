<?php
session_start();
include '../../data/db.php';
include '../../classes/Admin.php';
include '../../classes/Student.php';

if (isset($_SESSION["user_id"])) {
    $id = $_SESSION["user_id"];
    $account = $_SESSION["account"];

    if ($account == 2) {
        $instructor = $id;
    } else {
        $instructor = null;
    }

    $campusList = Admin::CampusList($conn, $id);

    $fullname = isset($_POST["fullname"]) && !empty($_POST["fullname"]) ? (string)$_POST["fullname"] : null;
    $campus = isset($_POST["campus"]) && !empty($_POST["campus"]) ? (string)$_POST["campus"] : $campusList;
    $eligible = isset($_POST["eligible"]) && !empty($_POST["eligible"]) ? (int)$_POST["eligible"] : null;
    $form = isset($_POST["form"]) && !empty($_POST["form"]) ? (int)$_POST["form"] : null;
    $year = isset($_POST["year"]) && !empty($_POST["year"]) ? (int)$_POST["year"] : 0;

    $page = isset($_POST["page"]) ? (int)$_POST["page"] : 1;
    $limit = (isset($_POST["limit"]) && is_numeric($_POST["limit"]) && (int)$_POST["limit"] > 0)
    ? (int)$_POST["limit"]
    : 25;

    $offset = ($page - 1) * $limit;

    $students = Student::SelectStudents($conn, $instructor, $fullname, $eligible, $form, $campus, $year, $limit, $offset);
    Student::DisplayStudents($conn, $students);

    $totalCount = Student::GetTotalStudents($conn, $instructor, $fullname, $eligible, $campus, $year, $limit);
    $totalPages = ceil($totalCount / $limit);

    Student::DisplayAjaxPages($page, $totalPages);
}
?>