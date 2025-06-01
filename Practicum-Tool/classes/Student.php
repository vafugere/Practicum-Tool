<?php
class Student {
    private $id;
    private $firstName;
    private $lastName;
    private $email;
    private $orgId;
    private $decision;
    private $secured;
    private $gradTrack;
    private $eligible;
    private $form;
    private $campus;
    private $year;
    private $instructor;

    public function __get($property) {
        return $this->$property;
    }
    public function __set($property, $value) {
        $this->$property = $value;
    }
    public function __construct($id, $firstName, $lastName, $email, $orgId, $decision, $secured, $gradTrack, $eligible, $form, $campus, $year, $instructor) {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->orgId = $orgId;
        $this->decision = $decision;
        $this->secured = $secured;
        $this->gradTrack = $gradTrack;
        $this->eligible = $eligible;
        $this->form = $form;
        $this->campus = $campus;
        $this->year = $year;
        $this->instructor = $instructor;
    }
    public static function GetStudentById($conn, $id) {
        $stmt = $conn->prepare('CALL GetStudentById(?)');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->bind_result($dbId, $dbFname, $dbLname, $dbEmail, $dbOrgId, $dbDecision, $dbSecured, $dbGradTrack, $dbEligible, $dbForm, $dbCampus, $dbYear, $dbInstructor);
        $student = null;
        if ($stmt->fetch()) {
            $student = new Student($dbId, $dbFname, $dbLname, $dbEmail, $dbOrgId, $dbDecision, $dbSecured, $dbGradTrack, $dbEligible, $dbForm, $dbCampus, $dbYear, $dbInstructor);
        }
        $stmt->close();
        $conn->next_result();
        return $student;
    }
    public static function GetStudentByOrgId($conn, $orgId) {
        $stmt = $conn->prepare('CALL GetStudentByOrgId(?)');
        $stmt->bind_param('s', $orgId);
        $stmt->execute();
        $stmt->bind_result($dbId, $dbFname, $dbLname, $dbEmail, $dbOrgId, $dbDecision, $dbSecured, $dbGradTrack, $dbEligible, $dbForm, $dbCampus, $dbYear, $dbInstructor);
        $student = null;
        if ($stmt->fetch()) {
            $student = new Student($dbId, $dbFname, $dbLname, $dbEmail, $dbOrgId, $dbDecision, $dbSecured, $dbGradTrack, $dbEligible, $dbForm, $dbCampus, $dbYear, $dbInstructor);
        }
        $stmt->close();
        $conn->next_result();
        return $student;
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
# Upload: insert students from execel file
    public static function InsertStudents($conn, $student) {
        $stmt = $conn->prepare('CALL InsertStudents(?,?,?,?,?,?,?,?,?,?,?)');
        $stmt->bind_param(
            'sssiiiiiiii', 
            $student->firstName, 
            $student->lastName, 
            $student->orgId, 
            $student->decision,
            $student->secured, 
            $student->gradTrack,
            $student->eligible,
            $student->form,
            $student->campus, 
            $student->year,
            $student->instructor
        );
        $stmt->execute();
        $stmt->close();
    }
    public static function SelectStudents($conn, $instructor, $fullname, $eligible, $form, $campus, $year, $limit, $offset) {
        $stmt = $conn->prepare('CALL SelectStudents(?,?,?,?,?,?,?,?)');
        $stmt->bind_param('isiisiii', $instructor, $fullname, $eligible, $form, $campus, $year, $limit, $offset);
        $stmt->execute();
        $result = $stmt->get_result();

        $students = [];

        while ($row = $result->fetch_assoc()) {
            $student = new Student(
                $row["studentId"],
                $row["firstName"],
                $row["lastName"],
                $row["email"],
                $row["orgId"],
                $row["decision"],
                $row["secured"],
                $row["gradTrack"],
                $row["eligible"],
                $row["form"],
                $row["campus"],
                $row["year"],
                $row["instructor"]
            );
            $students[] = $student;
        }
        $stmt->close();
        $conn->next_result();
        return $students;
    }
    public static function DisplayStudents($conn, $students) {
        $campusLocations = self::CampusLocations($conn);

        echo '<table id="student_list" border="1">';
        echo '<thead>';
        echo '<tr>
                <th>Name</th>
                <th>Org Defined ID</th>
                <th>Campus</th>
                <th>Year</th>
                <th>Practicum Decision</th>
                <th>Practicum Secured</th>
                <th>Grad Track</th>
                <th>Eligibility</th>
                <th>Form Submitted</th>
                <th>Comment</th>
            </tr>';
        echo '</thead>';
        echo '<tbody>';

        if (!empty($students)) {
            foreach ($students as $student) {
                echo '<tr>';
                echo '<td>' . $student->lastName . ', ' . $student->firstName . '</td>';
                echo '<td>' . $student->orgId . '</td>';
                echo '<td>' . $campusLocations[$student->campus] . '</td>';
                echo '<td>' . $student->year . '</td>';
                echo '<td>' . self::SelectStatus($conn, 'decision', $student->id, $student->decision) . '</td>';
                echo '<td>' . self::SelectStatus($conn, 'secured', $student->id, $student->secured) . '</td>';
                echo '<td>' . self::SelectStatus($conn, 'gradTrack', $student->id, $student->gradTrack) . '</td>';
                echo '<td>' . self::DisplayEligibility($student->eligible) . '</td>';
                echo '<td>' . self::SelectStatus($conn, 'form', $student->id, $student->form) . '</td>';
                echo '<td>' . self::CommentBox($student->id) . '</td>';
                echo '</tr>';
            }
        } else {
            echo '<tr><td colspan="9">No results found</td></tr>';
        }

        echo '</tbody>';
        echo '</table>';
    }
# Student list: get total amount of students
    public static function GetTotalStudents($conn, $instructor, $fullname, $eligible, $campus, $year, $limit) {
        $stmt = $conn->prepare('CALL GetTotalStudents(?,?,?,?,?,?)');
        $stmt->bind_param('isisii', $instructor, $fullname, $eligible, $campus, $year, $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        $total = 0;
        if ($row = $result->fetch_assoc()) {
            $total = $row["total"];
        }
        $stmt->close();
        $conn->next_result();
        return $total;
    }
#Student list: pages
    public static function DisplayPages($page, $totalPages) {
        if ($totalPages <= 1) {
            return '';
        }
        echo '<div class="pagination">';
        echo '<div class="page-left">';
        if ($page > 1) {
            echo '<a href="?page=' . ($page - 1) . '" data-page="' . ($page - 1) . '" class="page-btn">Previous</a>';
        }
        echo '</div>';
        echo '<div class="page-right">';
        for ($i = 1; $i <= $totalPages; $i++) {
            $activeClass = ($i == $page) ? 'active' : '';
            echo '<a href="?page=' . $i . '" data-page="' . $i . '" class="page-btn ' . $activeClass . '">' . $i . '</a>';
        }
        if ($page < $totalPages) {
            echo '<a href="?page=' . ($page + 1) . '" data-page="' . ($page + 1) . '" class="page-btn">Next</a>';
        }
        echo '</div>';
        echo '</div>';
    }
    public static function DisplayAjaxPages($page, $totalPages) {
        if ($totalPages <= 1) return;
        echo '<div class="pagination">';
        echo '<div class="page-left">';
        if ($page > 1) {
            echo '<a href="#" class="page-btn" data-page="' . ($page - 1) . '">Previous</a>';
        }
        echo '</div>';
        echo '<div class="page-right">';
        for ($i = 1; $i <= $totalPages; $i++) {
            $active = ($i == $page) ? 'active' : '';
            echo '<a href="#" class="page-btn ' . $active . '" data-page="' . $i . '">' . $i . '</a>';
        }
        if ($page < $totalPages) {
            echo '<a href="#" class="page-btn" data-page="' . ($page + 1) . '">Next</a>';
        }
        echo '</div>';
        echo '</div>';
    }
# Student List: display campus location
    public static function CampusLocations($conn) {
        $stmt = $conn->prepare('CALL SelectCampus()');
        $stmt->execute();
        $result = $stmt->get_result();
        $campusLocations = [];
        while ($row = $result->fetch_assoc()) {
            $campusLocations[$row["campusId"]] = $row["location"];
        }
        $stmt->close();
        $conn->next_result();
        return $campusLocations;
    }
# Student List: select menu decision, secured, gradTrack, form
    public static function SelectStatus($conn, $name, $id, $status) {
        $string = '<select name="' . $name . '[' . $id . ']" class="list-select">';
        $stmt = $conn->prepare('CALL SelectStatus()');
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $selected = ($row["statusId"] == $status) ? 'selected' : '';
                $string .= '<option value="' . $row["statusId"] . '" ' . $selected . '>' . $row["status"] . '</option>';
            }
        }
        $stmt->close();
        $conn->next_result();

        $string .= '</select>';
        return $string;
    }
# Student List: display eligibility
    public static function DisplayEligibility($status) {
        if ($status == 2) {
            $string = "Eligible";
        } else if ($status == 3) {
            $string = "Not Eligible";
        } else {
            $string = "";
        }
        return $string;
    }
# Student List: display comment box
    public static function CommentBox($id) {
        $string = '<textarea class="list-textarea" name="comment[' . $id .']" rows="1" cols="25"></textarea>';
        return $string;
    }
# List_proc: get current dicision
    public static function CurrentDecision($conn, $id) {
        $stmt = $conn->prepare('CALL CurrentDecision(?)');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        $conn->next_result();
        return $row ? $row["decision"] : null;
    }
# List_proc: override decision
    public static function OverrideDecision($conn, $status, $id) {
        $stmt = $conn->prepare('CALL OverrideDecision(?,?)');
        $stmt->bind_param('ii', $status, $id);
        $stmt->execute();
        $stmt->close();
    }
# List_proc: get current secured practicum
    public static function CurrentSecured($conn, $id) {
        $stmt = $conn->prepare('CALL CurrentSecured(?)');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        $conn->next_result();
        return $row ? $row["secured"] : null;
    }
# List_proc: update secured
    public static function UpdateSecured($conn, $status, $id) {
        $stmt = $conn->prepare('CALL UpdateSecured(?,?)');
        $stmt->bind_param('ii', $status, $id);
        $stmt->execute();
        $stmt->close();
    }
# List_proc: get current grad tack
    public static function CurrentGradTrack($conn, $id) {
        $stmt = $conn->prepare('CALL CurrentGradTrack(?)');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        $conn->next_result();
        return $row ? $row["gradTrack"] : null;
    }
#List_proc: update grad track
    public static function UpdateGradTrack($conn, $status, $id) {
        $stmt = $conn->prepare('CALL UpdateGradTrack(?,?)');
        $stmt->bind_param('ii', $status, $id);
        $stmt->execute();
        $stmt->close();
    }
# List_proc: update eligibility
    public static function UpdateEligibility($conn, $status, $id) {
        $stmt = $conn->prepare('CALL UpdateEligibility(?,?)');
        $stmt->bind_param('ii', $status, $id);
        $stmt->execute();
        $stmt->close();
    }
# List_proc current form
    public static function CurrentForm($conn, $id) {
        $stmt = $conn->prepare('CALL CurrentForm(?)');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        $conn->next_result();
        return $row ? $row["form"] : null;
    }
# List_proc: update form
    public static function UpdateForm($conn, $status, $id) {
        $stmt = $conn->prepare('CALL UpdateForm(?,?)');
        $stmt->bind_param('ii', $status, $id);
        $stmt->execute();
        $stmt->close();
    }
# List_proc: insert comments
    public static function InsertComment($conn, $studentId, $adminId, $comment) {
        $stmt = $conn->prepare('CALL InsertComment(?,?,?)');
        $stmt->bind_param('iis', $studentId, $adminId, $comment);
        $stmt->execute();
        $stmt->close();
    }
# Sort list: student name
    public static function ViewStudentName() {
        echo '<input type="text" id="filter_name" name="name" class="filter" placeholder="Type Student Name">';
    }
# Sort list: by campus
    public static function ViewCampus($conn, $campusList) {
        echo '<select id="filter_campus" name="campus" class="filter">';
        echo '<option value="">Campus</option>';

        if(empty(trim($campusList))) {
            $campusArray = [];
        } else {
            $campusArray = explode(',', $campusList);
        }

        if (empty($campusArray)) {
            echo '<option value="">No Campus Available</option>';
        } else {
            $stmt = $conn->prepare('CALL SelectCampus()');
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    if (in_array($row["campusId"], $campusArray)) {
                        echo '<option value="' . $row["campusId"] . '">' . $row["location"] . '</option>';
                    }
                }
            }
            $stmt->close();
            $conn->next_result();
        }
        echo '<option value="0">View All</option>';
        echo '</select>';
    }
# Sort student list: by year
    public static function ViewYear($conn, $inId) {
        echo '<select id="filter_year" name="year" class="filter">';
        echo '<option value="">Year</option>';

        $stmt = $conn->prepare('CALL ViewYears(?)');
        $stmt->bind_param('i', $inId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<option value="' . $row["year"] . '">' . $row["year"] . '</option>';
            }
        }
        $stmt->close();
        $conn->next_result();

        echo '<option value="0">View All</option>';
        echo '</select>';
    }
    public static function ViewAdminYear($conn) {
        echo '<select id="filter_year" name="year" class="filter">';
        echo '<option value="">Year</option>';

        $stmt = $conn->prepare('CALL ViewAdminYear()');
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<option value="' . $row["year"] . '">' . $row["year"] . '</option>';
            }
        }
        $stmt->close();
        $conn->next_result();

        echo '<option value="0">View All</option>';
        echo '</select>';
    }
# Sort student list: by eligibility
    public static function ViewEligible() {
        echo '<select id="filter_eligible" name="eligible" class="filter">';
        echo '<option value="">Eligibility</option>';
        echo '<option value="2">Eligible</option>';
        echo '<option value="3">Not Eligible</option>';
        echo '<option value="">View All</option>';
        echo '</select>';
    }
# Sort student list: by form submitted
    public static function ViewForm($conn) {
        echo '<select id="filter_form" name="form" class="filter">';
        echo '<option value="">Form Submitted</option>';
        $stmt = $conn->prepare('CALL SelectStatus()');
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<option value="' . $row["statusId"] . '">' . $row["status"] . '</option>';
            }
        }
        $stmt->close();
        $conn->next_result();

        echo '<option value="">View All</option>';
        echo '</select>';
    }
# Sort student list: amount per page
    public static function ViewAmount() {
        echo '<select id="filter_limit" name="limit" class="filter">';
        echo '<option value="">View</option>';
        echo '<option value="25">25 per page</option>';
        echo '<option value="50">50 per page</option>';
        echo '<option value="75">75 per page</option>';
        echo '<option value="100">100 per page</option>';
        echo '</select>';
    }
# Student status: check if student has comments
    public static function CountStudentComments($conn, $studentId) {
        $stmt = $conn->prepare('CALL CountStudentComments(?)');
        $stmt->bind_param('i', $studentId);
        $stmt->execute();
        $result = $stmt->get_result();
        $count = 0;
        if ($row = $result->fetch_assoc()) {
            $count = $row["count"];
        }
        $stmt->close();
        //$stmt->next_result();
        return $count;
    }
# Student status: get comments
    public static function GetStudentComments($conn, $studentId) {
        $stmt = $conn->prepare('CALL GetStudentComments(?)');
        $stmt->bind_param('i', $studentId);
        $stmt->execute();
        $comments = [];
        $stmt->bind_result($comment, $adminId, $date);
        while ($stmt->fetch()) {
            $comments[] = [
                "comment" => $comment,
                "adminId" => $adminId,
                "date" => $date
            ];
        }
        $stmt->close();
        $conn->next_result();
        return $comments;
    }
# Student status: get formatted due date
    public static function GetFormattedDates($conn, $null) {
        $stmt = $conn->prepare('CALL GetDueDates(?)');
        $stmt->bind_param('s', $null);
        $stmt->execute();
        $result = $stmt->get_result();
        $dueDates = [];
        while ($row = $result->fetch_assoc()) {
            $dueDates[$row["campus"]] = date('F j, Y g:i A', strtotime($row["dueDate"]));
        }
        $stmt->close();
        $conn->next_result();
        return $dueDates;
    }
# Student status: get due date
    public static function GetDueDates($conn, $null) {
        $stmt = $conn->prepare('CALL GetDueDates(?)');
        $stmt->bind_param('s', $null);
        $stmt->execute();
        $result = $stmt->get_result();
        $dueDates = [];
        while ($row = $result->fetch_assoc()) {
            $dueDates[$row["campus"]] = $row["dueDate"];
        }
        $stmt->close();
        $conn->next_result();
        return $dueDates;
    }

}
?>