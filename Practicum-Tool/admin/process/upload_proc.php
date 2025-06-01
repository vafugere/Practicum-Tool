<?php
session_start();
require '../../data/db.php';
include '../../classes/Student.php';
require '../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

if ($_FILES['excel_file']['error'] == UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['excel_file']['tmp_name'];

# Load spreadsheet
    $spreadsheet = IOFactory::load($fileTmpPath);
    $sheet = $spreadsheet->getActiveSheet();
    $data = $sheet->toArray();

# Check if file is empty or containers only headers
    if (count($data) <= 1) {
        $msg = "The upload file is empty, please select another file";
        header("Location: ../upload.php?message=" . urlencode($msg));
        exit();
    }

    function NormalizeHeaders($header) {
        return preg_replace('/\s+/', '', trim($header));
    }

# Validate header format
    $expectedHeaders = ["LastNameFirstName", "Username", "OrgDefinedID"];
    $actualHeaders = array_map('NormalizeHeaders', array_slice($data[0], 0, 3));
    if ($actualHeaders !== $expectedHeaders) {
        $msg = "Please select a file with columns: Last Name First Name, Username, Org Defined ID";
        header("Location: ../upload.php?message=" . urlencode($msg));
    }

# Remove first row (header)
    $headers = $data[0];
    unset($data[0]);

# Insert students
    foreach ($data as $row) {
        $fullname = $conn->real_escape_string($row[0]);
        if (empty($fullname)) {
            continue;
        }
        $names = explode(",", $fullname);
        $lastName = isset($names[0]) ? trim($names[0]) : "";
        $firstName = isset($names[1]) ? trim($names[1]) : "";
        $orgId = $conn->real_escape_string($row[2]);

        $campus = $_POST["campus"];
        $year = $_POST["year"];
        $instructor = $_SESSION["user_id"];

        $student = new Student(null, $firstName, $lastName, null, $orgId, 1, 1, 1, 1, 1, $campus, $year, $instructor);
        Student::InsertStudents($conn, $student);
    }

    unlink($fileTmpPath);
}
$conn->close();
header("Location: ../list.php");
?>