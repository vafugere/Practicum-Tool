<?php
class Notification {
    public static function InsertNotification($conn, $msg, $toId, $fromId, $isComment) {
        $stmt = $conn->prepare('CALL InsertNotification(?,?,?,?)');
        $stmt->bind_param('siii', $msg, $toId, $fromId, $isComment);
        $stmt->execute();
        $stmt->close();
    }
    public static function GetNotifications($conn, $toId) {
        $stmt = $conn->prepare('CALL GetNotifications(?)');
        $stmt->bind_param('i', $toId);
        $stmt->execute();
        $result = $stmt->get_result();
        $notifications = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $notifications[] = [
                    "message" => $row["message"],
                    "fromId" => $row["fromId"],
                    "date" => $row["date"],
                    "isComment" => $row["isComment"]
                ];
            }
        }
        $stmt->close();
        $conn->next_result();
        return $notifications;
    }
    public static function GetAllNotifications($conn, $toId, $limit, $offset) {
        $stmt = $conn->prepare('CALL GetAllNotifications(?,?,?)');
        $stmt->bind_param('iii', $toId, $limit, $offset);
        $stmt->execute();
        $result = $stmt->get_result();
        $notifications = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $notifications[] = [
                    "message" => $row["message"],
                    "fromId" => $row["fromId"],
                    "date" => $row["date"],
                    "isComment" => $row["isComment"]
                ];
            }
        }
        $stmt->close();
        $conn->next_result();
        return $notifications;
    }
    public static function CountNotifications($conn, $toId) {
        $stmt = $conn->prepare('CALL CountAllNotifications(?)');
        $stmt->bind_param('i', $toId);
        $stmt->execute();
        $stmt->bind_result($dbCount);
        $count = 0;
        if ($stmt->fetch()) {
            $count = $dbCount;
        }
        $stmt->close();
        $conn->next_result();
        return $count;
    }
    public static function DisplayStudentNotifications($conn, $toId) {
        $notifications = self::GetNotifications($conn, $toId);
        if (empty($notifications)) {
            echo '<div class="note">You have 0 notifications</div>';
            return;
        }
        $total = count($notifications);
        foreach ($notifications as $index => $note) {
            echo '<div class="note">';
            if ($note["isComment"] == 1) {
                $admin = User::GetUserById($conn, $note["fromId"]);
                echo $note["message"] . '<br><span class="text-green">' . $admin->firstName . ' ' . $admin->lastName . '</span>';
            } else {
                echo $note["message"];
            }
            echo '<br><span class="note-date">' . $note["date"] . '</span>';

            if ($index < $total - 1) {
                echo '<hr>';
            }
            
            echo '</div>';
        }
        echo '<div class="text-right"><a href="notifications.php" class="note-link">View All</a></div>';
    }
    public static function DisplayAllStudentNotifications($conn, $notifications) {
        if (empty($notifications)) {
            echo '<div class="notif">You have 0 notifications</div>';
            return;
        }
        $total = count($notifications);
        foreach ($notifications as $index => $note) {
            echo '<div class="notif">';
            if ($note["isComment"] == 1) {
                $admin = User::GetUserById($conn, $note["fromId"]);
                echo $note["message"] . ' <a href="index.php" class="notif-link">' . $admin->firstName . ' ' . $admin->lastName . '</a>';
            } else {
                echo $note["message"];
            }
            echo '<br><span class="notif-date">' . $note["date"] . '</span>';

            if ($index < $total - 1) {
                echo '<hr>';
            }
            
            echo '</div>';
        }
    }
    public static function GetAdminIds($conn) {
        $stmt = $conn->prepare('CALL GetAdminIds()');
        $stmt->execute();
        $result = $stmt->get_result();
        $admins = [];
        while ($row = $result->fetch_assoc()) {
            $admins[] = $row["userId"];
        }
        $stmt->close();
        $conn->next_result();
        return $admins;
    }
    public static function DisplayAdminNotifications($conn, $userId) {
        $notifications = self::GetNotifications($conn, $userId);
        if (empty($notifications)) {
            echo '<div class="note">You have 0 notifications</div>';
            return;
        }
        $total = count($notifications);
        $locations = Student::CampusLocations($conn);
        foreach ($notifications as $index => $note) {
            $studentId = $note["fromId"];
            $student = Student::GetStudentById($conn, $studentId);
            echo '<div class="note">';
            echo '<span class="text-green">' . $student->firstName . ' ' . $student->lastName . '</span>';
            echo ' ' . $note["message"];
            echo '<br><span class="note-date">' . $note["date"] . ' ' . $locations[$student->campus] . '</span>';
            if ($index < $total - 1) {
                echo '<hr>';
            }
            echo '</div>';
        }
        echo '<div class="text-right"><a href="notifications.php" class="note-link">View All</a></div>';
    }
    public static function DisplayAllAdminNotifications($conn, $notifications) {
        if (empty($notifications)) {
            echo '<div class="notif">You have 0 notifications</div>';
            return;
        }
        $total = count($notifications);
        $locations = Student::CampusLocations($conn);
        foreach ($notifications as $index => $note) {
            $studentId = $note["fromId"];
            $student = Student::GetStudentById($conn, $studentId);
            echo '<div class="notif">';
            echo '<span class="text-green">' . $student->firstName . ' ' . $student->lastName . '</span>';
            echo ' ' . $note["message"];
            echo '<br><span class="notif-date">' . $note["date"] . ' ' . $locations[$student->campus] . '</span>';
            if ($index < $total - 1) {
                echo '<hr>';
            }
            echo '</div>';
        }
    }
    public static function DisplayPages($page, $totalPages) {
        if ($totalPages <= 1) {
            return '';
        }
        echo '<div class="pagination">';
        echo '<div class="page-left">';
        if ($page > 1) {
            echo '<a href="?page=' . ($page - 1) . '" class="page-btn">Previous</a>';
        }
        echo '</div>';
        echo '<div class="page-right">';
        for ($i = 1; $i <= $totalPages; $i++) {
            $activeClass = ($i == $page) ? 'active' : '';
            echo '<a href="?page=' . $i . '" class="page-btn ' . $activeClass . '">' . $i . '</a>';
        }
        if ($page < $totalPages) {
            echo '<a href="?page=' . ($page + 1) . '" class="page-btn">Next</a>';
        }
        echo '</div>';
        echo '</div>';
    }

}
?>