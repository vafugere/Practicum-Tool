<?php
session_start();
require '../data/db.php';
include '../classes/User.php';
include '../classes/Student.php';
include '../classes/Notification.php';

if (isset($_SESSION["user_id"])) {
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["decision"])) {
        $id = $_SESSION["user_id"];
        $decision = $_POST["decision"];
        $studentId = User::GetStudentId($conn, $id);
        $currentDecision = Student::CurrentDecision($conn, $studentId);

        if ($currentDecision == $decision) {
            header("Location: ../index.php");
            exit();
        } else {
            $result = Student::UpdateDecision($conn, $decision, $studentId);
        }

        if ($result) {
            $adminIds = Notification::GetAdminIds($conn);
            $student = Student::GetStudentById($conn, $studentId);
            $instructor = $student->instructor;

            if ($decision == 2 || $decision == 3) {
                $msg = ($decision == 2) ? "has opted in" : "has opted out";

                Notification::InsertNotification($conn, $msg, $instructor, $studentId, 0);
                foreach ($adminIds as $admin) {
                    Notification::InsertNotification($conn, $msg, $admin, $studentId, 0);
                }
            }
            
            header("Location: ../index.php");
            exit();
        } else {
            $msg = "Error: Unable to update record";
            header("Location: ../decision_form.php?message=" . urlencode($msg));
            exit();
        }
    }
} else {
    header("Location: login.php");
    exit();
}
?>