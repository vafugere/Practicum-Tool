<?php
session_start();
require 'includes/student_init.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
} else {
    $id = $_SESSION["user_id"];
    $user = User::GetUserById($conn, $id);
    $student = Student::GetStudentByOrgId($conn, $user->orgId);
    $currentDate = new DateTime();
    $dueDates = Student::GetDueDates($conn, null);
    $dueDate = (!empty($dueDates[$student->campus])) ? new DateTime($dueDates[$student->campus]) : null;

    if ($student->decision == 1 && (!isset($dueDate) || empty($dueDate) || $currentDate < $dueDate)) {
        header("Location: decision_form.php");
        exit();
    }
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
   echo "<script>alert($message); window.location.href = 'index.php';</script>";
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
           
        <?php
        $year = ($dueDate instanceof DateTime) ? $dueDate->format("Y") : date("Y");
        if ($student->eligible == 2) {
            echo    '<div class="banner-green">You are eligible for the ' . $year . ' practicum</div>';
        } else if ($student->eligible == 3) {
            echo    '<div class="banner-blue">You are not eligible for the ' . $year . ' practicum</div>';
        } else {
            echo    '<div class="banner-blue">Your eligibility is still in process</div>';
        }

        if ($student->decision == 2 || $student->decision == 3) {
            $msg = ($student->decision == 2) ? "You have opted in" : "You have opted out";
            echo    '<div class="banner-blue">' . $msg . '</div>';
        }

        $formatDate = Student::GetFormattedDates($conn, null);
        echo    '<div class="title-blue w-800">
                    <img src="images/icons/clock.png" width="28px" height="28px" alt="Clock Icon">
                        Due Date
                    </div>';
        echo    '<div class="box-blue w-800">';

        if (!empty($formatDate[$student->campus])) {
            echo    '<p class="text-blue">' . $formatDate[$student->campus] . '</p>';
        } else {
            echo    '<p>Due date is to be determined</p>';
        }

        if (empty($formatDate[$student->campus]) || $currentDate < $dueDate) {
            echo    '<a href="decision_form.php" class="btn-blue">Change Decision</a>';
        }

        echo    '</div>';

        $count = Student::CountStudentComments($conn, $student->id);
        if ($count !== 0) {
            echo    '<div class="title-green w-800">
                        <img src="images/icons/chat.png" alt="Chat Icon" width="28px" height="28px">
                        Comments
                    </div>';

            echo    '<div class="box-green w-800">';

            $comments = Student::GetStudentComments($conn, $student->id);
            $lastComment = end($comments);
            foreach ($comments as $comment) {
                $instructor = User::GetUserById($conn, $comment["adminId"]);
            
                echo    '<p>' . $comment["comment"] . '<br>
                            <span class="text-green">' . $instructor->firstName . ' ' . $instructor->lastName . '</span>';
                echo    '<br><span class="note-date">' . $comment["date"] . '</span>';

                if ($comment !== $lastComment) {
                        echo    '<hr>';
                }
                echo    '</p>';
            }
            echo    '</div>';
        }
        ?>

        </div>
    </main>
    <?php include_once("includes/footer.php"); ?>
</body>
</html>