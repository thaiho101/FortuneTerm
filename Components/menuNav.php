<?php
session_start(); // Start session before using $_SESSION
if ($_SERVER['REQUEST_URI'] === "/" || strpos($_SERVER['REQUEST_URI'], "index.php") !== false) {
    $_SESSION['activeMenu'] = "dashboard";
}

//default selected for the dashboard at the begining of first loading page
if(!isset($_SESSION['activeMenu'])) {
    $_SESSION['activeMenu'] = "dashboard";
}

// Set active menu on SESSION
if($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST["dashboard"])) {
    $_SESSION['activeMenu'] = "dashboard";
} else if($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST["myAccount"])) {
    $_SESSION['activeMenu'] = "myAccount";
} else if($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST["setting"])) {
    $_SESSION['activeMenu'] = "setting";
} 


echo $_SESSION['activeMenu'];

//Default class
// $menuButtonSelected = 'unSelected';
$menuDashboardSelected = 'unSelected';
$menuMyaccountSelected = 'unSelected';
$menuSettingSelected = 'unSelected';
//Configure selected class
if(isset($_SESSION['activeMenu']))
{
    if($_SESSION['activeMenu'] === "dashboard") {
        $menuDashboardSelected = "menuButtonSelected";
    } elseif ($_SESSION['activeMenu'] === "myAccount") {
        $menuMyaccountSelected = "menuButtonSelected";
        // $menuDashboardDefault = 'unSelected';
    } elseif ($_SESSION['activeMenu'] === "setting") {
        $menuSettingSelected = "menuButtonSelected";
        // $menuDashboardDefault = 'unSelected';
    }
    // header("Location: " . $_SERVER['PHP_SELF']); // Prevent resubmission
    // exit();
}





?>
<head>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.1/anime.min.js"></script>
</head>
<div id='menuNavigationDiv'>
    <div id='menuNav'>
        <div class='buttonLinks'>
            <form method='post' action="../">
                <button type='submit' name='dashboard' class='<?php echo $menuDashboardSelected . " " . $menuDashboardDefault;?>'>
                    <div class='menuButtonGap'>
                        <i class='fas fa-cloud-sun iconMenu'></i>
                        <div class='buttonLabelLinks'>Dashboard</div>
                    </div>
                </button>
            </form>
        </div>
        <div class='buttonLinks'>
            <form method='post' action="/myAccount">
                <button type='submit' name='myAccount' class='<?php echo $menuMyaccountSelected;?>'>
                    <i class='fas fa-user-tie iconMenu'></i>
                    <div class='buttonLabelLinks'>My Account</div>
                </button>
            </form>
        </div>
        <div class='buttonLinks'>
            <form method='post' action="/setting">
                <button type='submit' name='setting' class='<?php echo $menuSettingSelected;?>'>
                        <i class='fas fa-wrench iconMenu'></i>
                        <div class='buttonLabelLinks'>Setting</div>
                </button>
            </form>
        </div>
    </div>
</div>

