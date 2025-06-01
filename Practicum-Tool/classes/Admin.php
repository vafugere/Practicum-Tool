<?php
require_once 'User.php';
class Admin extends User {
    private array $campus = [];

    public function __construct($id, $firstName, $lastName, $email, $password, $account, $date, array $campus = []) {
        parent::__construct($id, $firstName, $lastName, $email, null, $password, $account, $date);
        $this->campus = $campus;
    }
    public function __get($property) {
        return $this->$property;
    }
    public function __set($property, $value) {
        $this->$property = $value;
    }
    public function addCampus($campus): void {
        if (!in_array($campus, $this->campus)) {
            $this->campus[] = $campus;
        }
    }
    public function removeCampus($campus): void {
        $index = array_search($campus, $this->campus);
        if ($index !== false) {
            unset($this->campus[$index]);
            $this->campus = array_values($this->campus);
        }
    }
    public function getCampus(): array {
        return $this->campus;
    }
    public static function GetAdminById($conn, $id) {
        $stmt = $conn->prepare('CALL GetAdminById(?)');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->bind_result($dbId, $dbFname, $dbLname, $dbEmail, $dbAccount, $dbCampus, $dbDate);
        $admin = null;
        if ($stmt->fetch()) {
            $admin = new Admin($dbId, $dbFname, $dbLname, $dbEmail, null, $dbAccount, $dbDate, [$dbCampus]);
        }
        $stmt->close();
        $conn->next_result();

        if ($admin) {
            if ($admin->account == 2) {
                $campusArray = self::GetAdditionalCampus($conn, $id);
                foreach ($campusArray as $campus) {
                    $admin->addCampus($campus);
                }
            }
            else if ($admin->account == 3) {
                $campusArray = self::GetAllCampus($conn);
                foreach ($campusArray as $campus) {
                    if ($campus === $admin->campus[0]) {
                        continue;
                    }
                    $admin->addCampus($campus);
                }
            }
        } 
        return $admin;
    }
# Get additional campus
    public static function GetAdditionalCampus($conn, $id) {
        $stmt = $conn->prepare('CALL GetInstructorCampus(?)');
        $stmt->bind_param('i', $id);
        $campusArray = [];
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $campusArray[] = $row["campus"];
            }
        }
        $stmt->close();
        $conn->next_result();
        return $campusArray;
    }
# Get all campus
    public static function GetAllCampus($conn) {
        $stmt = $conn->prepare('CALL SelectCampus()');
        $campusArray = [];
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            while ($row = $result->fetch_assoc())
            $campusArray[] = $row["campusId"];
        }
        $stmt->close();
        $conn->next_result();
        return $campusArray;
    }
# Coma seperated list of all campus
    public static function CampusList($conn, $id) {
        $admin = self::GetAdminById($conn, $id);
        if ($admin) {
            $campusList = implode(',', $admin->campus);
        }
        return $campusList;
    }
# Create Account: create admin or instructor
    public static function CreateAdmin($conn, $admin) {
        $stmt = $conn->prepare('CALL CreateAdmin(?,?,?,?,?,?, @userId)');
        $stmt->bind_param('ssssii', $admin->firstName, $admin->lastName, $admin->email, $admin->password, $admin->account, $admin->campus[0]);
        $stmt->execute();
        $stmt->close();

        $result = $conn->query("SELECT @userId AS userId");
        $row = $result->fetch_assoc();

        if ($row) {
            $userId = $row["userId"];

            self::InsertTempPassword($conn, $userId, $admin->password);

            if (count($admin->campus) > 1) {
                foreach(array_slice($admin->campus, 1) as $campus) {
                    self::InstructorCampus($conn, $userId, $campus);
                }
            }
        }
        header("Location: ../temp.php");
    }
# Create Account: insert instructor's campuses
    public static function InstructorCampus ($conn, $userId, $campus) {
        $stmt = $conn->prepare('CALL InstructorCampus(?,?)');
        $stmt->bind_param('ii', $userId, $campus);
        $stmt->execute();
        $stmt->close();
    }
# Create Account: create temp password
    public static function TempPassword() {
        $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $lowercase = 'abcdefghijklmnopqrstuvwxyz';
        $numbers = '0123456789';
        $specialChars = '@#$%^&*!';
        $length = 8;
    
        $password = '';
        $password .= $uppercase[random_int(0, strlen($uppercase) - 1)];
        $password .= $numbers[random_int(0, strlen($numbers) - 1)];   
        $password .= $specialChars[random_int(0, strlen($specialChars) - 1)];
    
        $allChars = $uppercase . $lowercase . $numbers . $specialChars;
        $remainingLength = $length - strlen($password);
    
        for ($i = 0; $i < $remainingLength; $i++) {
            $password .= $allChars[random_int(0, strlen($allChars) - 1)];
        }
    
        return str_shuffle($password);
    }
# Create Account: insert temp password
    public static function InsertTempPassword($conn, $userId, $temp) {
        $stmt = $conn->prepare('CALL InsertTempPassword(?,?)');
        $stmt->bind_param('is', $userId, $temp);
        $stmt->execute();
        $stmt->close();
    }
# Create Account: delete temp password
    public static function DeleteTempPassword($conn, $userId) {
        $stmt = $conn->prepare('CALL DeleteTempPassword(?)');
        $stmt->bind_param('i', $userId);
        if ($stmt->execute()) {
            $stmt->close();
            return true;
        }
        $stmt->close();
        return false;
    }
# Confirm temp password
    public static function MatchTemp($conn, $id) {
        $stmt = $conn->prepare('CALL MatchTemp(?)');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 1) {
            $stmt->close();
            return true;
        }
        $stmt->close();
        return false;
    }
# Create Account: select menu for account type
    public static function SelectAccountType($conn) {
        echo '<select id="account" name="account" required>';
        echo '<option value="">Select Account Type</option>';

        $stmt = $conn->prepare('CALL SelectAccountType()');
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                echo '<option value="' . $row["typeId"] . '">' . $row["type"] . '</option>';
            }
        }
        $stmt->close();
        $conn->next_result();

        echo '</select>';
    }
# Create Account: select menu for main campus
    public static function SelectMainCampus($conn) {
        echo '<select id="main_campus" name="main_campus" required>';
        echo '<option value="">Main Campus</option>';

        $stmt = $conn->prepare('CALL SelectCampus()');
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                echo '<option value="' . $row["campusId"] . '">' . $row["location"] . '</option>';
            }
        }
        $stmt->close();
        $conn->next_result();

        echo '</select>';
    }
# Create Account: checkbox for additional campus
    public static function CampusCheckbox($conn) {
        echo '<div id="checkbox_menu">';
        echo '<p class="label">Additional Campus:</p>';
        echo '<div class="form-border">';

        $stmt = $conn->prepare('CALL SelectCampus()');
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                echo '<label><input type="checkbox" name="campus[]" value="' . $row["campusId"] . '">' . $row["location"] . '</label><br>';
            }
        }
        $stmt->close();
        $conn->next_result();

        echo '</div>';
        echo '</div>';
    }
# Upload: select campus
    public static function SelectCampus($conn, $id) {
        $admin = self::GetAdminById($conn, $id);

        echo '<select id="campus" name="campus" required>';
        echo '<option value="">Select Campus</option>';

        if (empty($admin->campus)) {
            echo '<option value="-1">No campus available</option>';
        } else {
            $stmt = $conn->prepare('CALL SelectCampus()');
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    if (in_array($row["campusId"], $admin->campus)) {
                    echo '<option value="' . $row["campusId"] . '">' . $row["location"] . '</option>';
                    }
                }
            }
            $stmt->close();
            $conn->next_result();
        }
        echo '</select>'; 
    }
# Upload: select menu for Years (Today to 10 years from now)
    public static function SelectYear() {
        $currentYear = date("Y");
        $endYear = $currentYear + 10;

        echo '<select id="year" name="year" required>';
        echo '<option value="">Year</option>';

        for ($year = $currentYear; $year <= $endYear; $year++) {
            echo '<option value="' . $year . '">' . $year . '</option>';
        }

        echo '</select>';
    }
# Due date: choose time
    public static function SelectTime() {
    echo '<select id="time" name="time" required>';
    echo '<option value="">Select Time</option>';
    for ($hour = 0; $hour < 24; $hour++) {
        for ($minute = 0; $minute < 60; $minute += 30) {
            $timeValue = sprintf('%02d:%02d:00', $hour, $minute);
            $displayTime = date('g:i A', strtotime($timeValue));
            echo '<option value="' . $timeValue . '">' . $displayTime . '</option>';
        }
    }
    echo '</select>';
    }
# Due date: update due date
    public static function UpdateDueDate($conn, $campus, $dueDate) {
        $stmt = $conn->prepare('CALL UpdateDueDate(?,?)');
        $stmt->bind_param('is', $campus, $dueDate);
        $stmt->execute();
        $stmt->close();
    }
# Due date: get campus name and due date
    public static function GetDueDates($conn, $campusList) {
        $stmt = $conn->prepare('CALL SelectCampus()');
        $stmt->execute();
        $result = $stmt->get_result();
        $campusLocations = [];
        while ($row = $result->fetch_assoc()) {
            $campusLocations[$row["campusId"]] = $row["location"];
        }
        $stmt->close();
        $conn->next_result();

        $stmt = $conn->prepare('CALL GetDueDates(?)');
        $stmt->bind_param('s', $campusList);
        $stmt->execute();
        $dueDates = [];
        $stmt->bind_result($campus, $date);
        while ($stmt->fetch()) {
            $dueDates[] = [
                "campus" => $campusLocations[$campus],
                "date" => date('F j, Y g:i A', strtotime($date))
            ];
        }
        $stmt->close();
        $conn->next_result();
        return $dueDates;
    }
# Due date: count due dates
    public static function CountDueDates($conn, $id) {
        $campusList = self::CampusList($conn, $id);
        $stmt = $conn->prepare('CALL CountDueDates(?)');
        $stmt->bind_param('s', $campusList);
        $stmt->execute();
        $result = $stmt->get_result();
        $count = 0;
        if ($row = $result->fetch_assoc()) {
            $count = $row["count"];
        }
        $stmt->close();
        $conn->next_result();
        return $count;
    }
# Due date: display due dates
    public static function DisplayDates($conn, $id) {
        $campusList = self::CampusList($conn, $id);
        $dueDates = self::GetDueDates($conn, $campusList);
        $total = self::CountDueDates($conn, $id);
        $i = 0;

        if ($total !== 0) {
            echo '<div class="title-blue w-600">';
            echo '<img src="../images/icons/clock.png" width="28px" height="28px" alt="Clock Icon">';
            echo 'Due Dates</div>';
            echo '<div class="box-blue w-600">';
            foreach ($dueDates as $date) {
                $i++;
                echo '<p class="med-text">';
                echo '<strong>' . $date["campus"] . ': </strong>';
                echo $date["date"];
                if ($i < $total) {
                    echo '<hr>';
                }
                echo '</p>';
            }
            echo '</div>';
        }
    }

    
}
?>