<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
    <?php include_once ("includes/timeout.php"); ?>
    <header>
        <div class="header">
            <div>
                <a href="index.php" id="home_link">
                    <img src="images/icons/home.png" id="home_img" width="28px" height="28px" alt="Home Icon" class="dots">
                </a>
                <a href="https://www.nbcc.ca" target="_blank">
                    <img src="images/NBCCLogo.png" height="30px" class="dots" alt="NBCC Logo" id="logo">
                </a>
                <div class="header-title">Practicum Tool</div>
            </div>

            <div class="right-side">
                <div class="notification">
                    <img src="images/icons/bell.png" id="btn_bell" width="28px" height="28px" alt="Bell Icon" class="dots">
                    <div class="notification-menu" id="notification_menu">
                        <?php $studentId = User::GetStudentId($conn, $id);
                                Notification::DisplayStudentNotifications($conn, $studentId); ?>
                    </div>
                </div>
                
                <?php include_once("includes/name_badge.php"); ?>
                
                <div class="dropdown">
                <img src="images/icons/settings.png" id="btn_settings" width="28px" height="28px" alt="Settings Icon" class="settings-icon">
                    <div class="dropdown-menu" id="dropdown_menu">
                        <a href="settings.php">Account Settings</a>
                        <a href="logout.php">Logout</a>
                    </div>
                </div>
            </div>
        </div>
        <hr>
    </header>
   
