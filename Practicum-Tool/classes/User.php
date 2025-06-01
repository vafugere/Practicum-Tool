<?php
class User {
    protected $id;
    protected $firstName;
    protected $lastName;
    protected $email;
    protected $orgId;
    protected $password;
    protected $account;
    protected $date;

    public function __get($property) {
        return $this->$property;
    }
    public function __set($property, $value) {
        $this->$property = $value;
    }
    public function __construct($id, $firstName, $lastName, $email, $orgId = null, $password, $account, $date) {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->orgId = $orgId;
        $this->password = $password;
        $this->account = $account;
        $this->date = $date;
    }
    public static function CreateUser($conn, $user) {
        $stmt = $conn->prepare('CALL CreateUser(?,?,?,?,?,?)');
        $stmt->bind_param('sssssi', $user->firstName, $user->lastName, $user->email, $user->orgId, $user->password, $user->account);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                $_SESSION["user_id"] = $row["user_id"];
                $_SESSION["first_name"] = $user->firstName;
                $_SESSION["last_name"] = $user->lastName;
                $_SESSION["account"] = $user->account;
                $stmt->close();
                header("Location: ../index.php");
                exit();
            }
        } else {
            $stmt->close();
            $msg = "Oops something went wrong, please try again";
            header("location: ../signup.php?message=" . urlencode($msg));
            exit();
        }
    }
    public static function GetUserByEmail ($conn, $email) {
        $stmt = $conn->prepare('CALL GetUserByEmail(?)');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->bind_result($dbId, $dbFname, $dbLname, $dbEmail, $dbOrgId, $dbPassword, $dbAccount, $dbDate);
        $user = null;
        if ($stmt->fetch()) {
            $user = new User($dbId, $dbFname, $dbLname, $dbEmail, $dbOrgId, $dbPassword, $dbAccount, $dbDate);
        }
        $stmt->close();
        $conn->next_result();
        return $user;
    }
    public static function GetUserById($conn, $id) {
        $stmt = $conn->prepare('CALL GetUserById(?)');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->bind_result($dbId, $dbFname, $dbLname, $dbEmail, $dbOrgId, $dbAccount, $dbDate);
        $user = null;
        if ($stmt->fetch()) {
            $user = new User($dbId, $dbFname, $dbLname, $dbEmail, $dbOrgId, null, $dbAccount, $dbDate);
        }
        $stmt->close();
        $conn->next_result();
        return $user;
    }
    public static function GetStudentId($conn, $userId) {
        $user = User::GetUserById($conn, $userId);
        $student = Student::GetStudentByOrgId($conn, $user->orgId);
        $studentId = $student->id;
        return $studentId;
    }
    public static function AuthenticateUser($conn, $email, $password) {
        $user = self::GetUserByEmail($conn, $email);
        if ($user) {
            if (password_verify($password, $user->password)) {
                $_SESSION["user_id"] = $user->id;
                $_SESSION["first_name"] = $user->firstName;
                $_SESSION["last_name"] = $user->lastName;
                $_SESSION["account"] = $user->account;

                switch ($user->account) {
                    case 1:
                        header("Location: ../index.php");
                        exit();
                    case 2:
                    case 3:
                        header("Location: ../admin/index.php");
                        exit();
                    default:
                        $msg = "Oops, something went wrong, please try again";
                        header("Location: ../login.php?message=" . urlencode($msg));
                        exit();
                }
            }
            else {
                $msg = "Incorrect password, please try again";
                header("Location: ../login.php?message=" . urlencode($msg));
                exit();
            }
        } else {
            $msg = "Email does not exist. please try again";
            header("Location: ../login.php?message=" . urlencode($msg));
            exit();
        }
    }
    public static function UpdateStudentEmail($conn, $email, $orgId) {
        $stmt = $conn->prepare('CALL UpdateStudentEmail(?,?)');
        $stmt->bind_param('ss', $email, $orgId);
        $stmt->execute();
        if ($stmt->affected_rows === 0) {
            $stmt->close();
            return false;
        }
        $stmt->close();
        return true;
    }
    public static function UpdateDecision($conn, $status, $studentId) {
        $stmt = $conn->prepare('CALL UpdateDecision(?,?)');
        $stmt->bind_param('ii', $status, $studentId);
        if ($stmt->execute()) {
            $stmt->close();
            return true;
        }
        $stmt->close();
        return false;
    }
    public static function UpdatePassword($conn, $password, $id) {
        $password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare('CALL UpdatePassword(?,?)');
        $stmt->bind_param('si', $password, $id);
        $stmt->execute();
        if ($stmt->affected_rows === 1) {
            $stmt->close();
            return true; 
        } 
        $stmt->close();
        return false;
    }
    // public static function UpdateFirstName($conn, $fname, $id) {
    //     $stmt = $conn->prepare('CALL UpdateFirstName(?,?)');
    //     $stmt->bind_param('si', $fname, $id);
    //     $stmt->execute();
    //     $stmt->close();
    // }
    // public static function UpdateLastName($conn, $lname, $id) {
    //     $stmt = $conn->prepare('CALL UpdateLastName(?,?)');
    //     $stmt->bind_param('si', $lname, $id);
    //     $stmt->execute();
    //     $stmt->close();
    // }
    // public static function UpdateEmail($conn, $email, $id) {
    //     $stmt = $conn->prepare('CALL UpdateEmail(?,?)');
    //     $stmt->bind_param('si', $email, $id);
    //     $stmt->execute();
    //     $stmt->close();
    // }
}
?>