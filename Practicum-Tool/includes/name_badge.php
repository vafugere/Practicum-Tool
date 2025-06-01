<?php
$fname = $_SESSION["first_name"];
$lname = $_SESSION["last_name"];
$fLetter = substr($fname, 0, 1);
$lLetter =substr($lname, 0, 1);

echo '<div class="badge">' . $fLetter . $lLetter . '</div>
    <div class="signature">' . $fname . ' ' . $lname . '</div>';
?>