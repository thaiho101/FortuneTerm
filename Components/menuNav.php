<?php
session_start(); // Always start session

$currentPage = basename($_SERVER['REQUEST_URI']); // Get current file name

// Ensure Dashboard is always selected on first load
if (!isset($_SESSION['activeMenu']) || $currentPage === "" || strtolower($currentPage) === "index.php" || strtolower($currentPage) === "index.html") {
    $_SESSION['activeMenu'] = "dashboard";
}

// Set active menu on session based on POST request
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    if (isset($_POST["dashboard"])) {
        $_SESSION['activeMenu'] = "dashboard";
    } elseif (isset($_POST["myAccount"])) {
        $_SESSION['activeMenu'] = "myAccount";
    } elseif (isset($_POST["setting"])) {
        $_SESSION['activeMenu'] = "setting";
    }
}

// Default class
$menuDashboardSelected = 'unSelected';
$menuMyaccountSelected = 'unSelected';
$menuSettingSelected = 'unSelected';

// Apply selected class based on session
if ($_SESSION['activeMenu'] === "dashboard") {
    $menuDashboardSelected = "menuButtonSelected";
} elseif ($_SESSION['activeMenu'] === "myAccount") {
    $menuMyaccountSelected = "menuButtonSelected";
} elseif ($_SESSION['activeMenu'] === "setting") {
    $menuSettingSelected = "menuButtonSelected";
}
?>
<div id='menuNavigationDiv'>
    <div id='menuNav'>
        <div class='buttonLinks'>
            <form method='post' action="../">
                <button type='submit' name='dashboard' class='unSelected <?php echo $menuDashboardSelected . " " . $menuDashboardDefault;?>'>
                    <div class='menuButtonGap'>
                        <i class='fas fa-cloud-sun iconMenu'></i>
                        <div class='buttonLabelLinks'>Dashboard</div>
                    </div>
                </button>
            </form>
        </div>
        <div class='buttonLinks'>
            <form method='post' action="/myAccount">
                <button type='submit' name='myAccount' class='unSelected <?php echo $menuMyaccountSelected;?>'>
                    <i class='fas fa-user-tie iconMenu'></i>
                    <div class='buttonLabelLinks'>My Account</div>
                </button>
            </form>
        </div>
        <div class='buttonLinks'>
            <form method='post' action="/setting">
                <button type='submit' name='setting' class='unSelected <?php echo $menuSettingSelected;?>'>
                        <i class='fas fa-wrench iconMenu'></i>
                        <div class='buttonLabelLinks'>Setting</div>
                </button>
            </form>
        </div>
    </div>
</div>

