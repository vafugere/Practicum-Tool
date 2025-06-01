<?php
session_start();
require '../../data/db.php';
include '../../classes/Student.php';
include '../../classes/User.php';
include '../../classes/Notification.php';

if (isset($_SESSION["user_id"])) {
    $adminId = $_SESSION["user_id"];
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $updated = false;

    if (isset($_POST["decision"])) {
        foreach($_POST["decision"] as $studentId => $decision) {
            $currentDecision = Student::CurrentDecision($conn, $studentId);
            if ($decision != $currentDecision) {
                Student::OverrideDecision($conn, $decision, $studentId);

                if ($decision == 2) {
                    $msg = "Your practicum decision has been changed to yes";
                } else if ($decision == 3) {
                    $msg = "Your practicum decision has been changed to no";
                }
                Notification::InsertNotification($conn, $msg, $studentId, $adminId, 0);

                $updated = true;
            }
        }
    }
    if (isset($_POST["secured"])) {
        foreach ($_POST["secured"] as $studentId => $secured) {
            $currentSecured = Student::CurrentSecured($conn, $studentId);
            if ($secured != $currentSecured) {
                Student::UpdateSecured($conn, $secured, $studentId);

                if ($secured == 2) {
                    $msg = "Your placement has been secured";
                    Notification::InsertNotification($conn, $msg, $studentId, $adminId, 0);
                }

                $student = Student::GetStudentById($conn, $studentId);
                if ($student->secured == 2 && $student->gradTrack == 2) {
                    Student::UpdateEligibility($conn, 2, $student->id);

                    $eligible_msg = "Your eligibility has been approved";
                    Notification::InsertNotification($conn, $eligible_msg, $studentId, $adminId, 0);

                } else if ($student->secured == 3) {
                    Student::UpdateEligibility($conn, 3, $studentId);

                    $eligible_msg = "You are not eligible: placement has not been secured";
                    Notification::InsertNotification($conn, $eligible_msg, $studentId, $adminId, 0);

                }

                $updated = true;
            }
        }
    }
    if (isset($_POST["gradTrack"])) {
        foreach ($_POST["gradTrack"] as $studentId => $gradTrack) {
            $currentGradTrack = Student::CurrentGradTrack($conn, $studentId);
            if ($gradTrack != $currentGradTrack) {
                Student::UpdateGradTrack($conn, $gradTrack, $studentId);

                if ($gradTrack == 2) {
                    $msg = "You are on grad track";
                    Notification::InsertNotification($conn, $msg, $studentId, $adminId, 0);
                } 

                $student = Student::GetStudentById($conn, $studentId);
                if ($student->secured == 2 && $student->gradTrack == 2) {
                    Student::UpdateEligibility($conn, 2, $studentId);

                    $eligible_msg = "Your eligibility has been approved";
                    Notification::InsertNotification($conn, $eligible_msg, $studentId, $adminId, 0);

                } else if ($student->gradTrack == 3) {
                    Student::UpdateEligibility($conn, 3, $studentId);

                    $eligible_msg = "You are not eligible: you are not on grad track";
                    Notification::InsertNotification($conn, $eligible_msg, $studentId, $adminId, 0);
                }

                $updated = true;
            }
        }
    }
    if (isset($_POST["form"])) {
        foreach($_POST["form"] as $studentId => $form) {
            $currentForm = Student::CurrentForm($conn, $studentId);
            if ($form != $currentForm) {
                Student::UpdateForm($conn, $form, $studentId);

                if ($form == 2) {
                    $msg = "Your Student Status Form has been sumbitted";
                    Notification::InsertNotification($conn, $msg, $studentId, $adminId, 0);
                }
                $updated = true;
            }
        }
    }
    if (isset($_POST["comment"])) {
        foreach ($_POST["comment"] as $studentId => $comment) {
            if (!empty(trim($comment))) {
                Student::InsertComment($conn, $studentId, $adminId, $comment);
                $msg = "You have a new comment from:";
                Notification::InsertNotification($conn, $msg, $studentId, $adminId, 1);
                $updated = true;
            }
        }
    }


    if ($updated) {
        $msg = "Saved changes";
        header("Location: ../list.php?message=" . urlencode($msg));
        exit();
    } else {
        $msg = "Oops something went wrong, please try again";
        header("Location: ../list.php?message=" . urlencode($msg));
        exit();
    }
}
?>