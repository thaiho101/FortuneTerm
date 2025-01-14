<?php
// require_once("../config.php");
$userId = $_SESSION['user_id']; 
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($conn->connect_error)
{
        die("Connect failed: " . $conn->connect_error);
}

$stmt = $conn->prepare("SELECT first_name, last_name, email FROM users WHERE user_id = ?");
$stmt->bind_param('i', $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $firstName = $row['first_name'];
    $lastName = $row['last_name'];
    $email = $row['email'];
} else {
    $firstName = "Guest"; // Default value if not found
}
$stmt->close();
////[Translation]/////////-->Header
if ($_SESSION['languageType'] == 'English') {
    $greeting = "Welcome, ";
    $dashboardTranslate = "Dashboard";
    $myAccountTranslate = "My Account";
    $settingTranslate = "Setting";
    $logOutTranslate = "Log out";
} else if ($_SESSION['languageType'] == 'Vietnamese') {
    $greeting = "Xin chào, ";
    $dashboardTranslate = "Bảng điều khiển";
    $myAccountTranslate = "Tài khoản của tôi";
    $settingTranslate = "Cài đặt";
    $logOutTranslate = "Đăng xuất";
} else if ($_SESSION['languageType'] == 'Spanish') {
    $greeting = "Bienvenido, ";
    $dashboardTranslate = "Tablero";
    $myAccountTranslate = "Mi Cuenta";
    $settingTranslate = "Configuración";
    $logOutTranslate = "Cerrar sesión";
} else if ($_SESSION['languageType'] == 'German') {
    $greeting = "Willkommen, ";
    $dashboardTranslate = "Instrumententafel";
    $myAccountTranslate = "Mein Konto";
    $settingTranslate = "Einstellungen";
    $logOutTranslate = "Abmelden";
} else if ($_SESSION['languageType'] == 'French') {
    $greeting = "Bienvenue, ";
    $dashboardTranslate = "Tableau de Bord";
    $myAccountTranslate = "Mon Compte";
    $settingTranslate = "Paramètres";
    $logOutTranslate = "Se déconnecter";
} else if ($_SESSION['languageType'] == 'Korean') {
    $greeting = "환영합니다, ";
    $dashboardTranslate = "대시보드";
    $myAccountTranslate = "내 계정";
    $settingTranslate = "설정";
    $logOutTranslate = "로그아웃";
} else if ($_SESSION['languageType'] == 'Chinese') {
    $greeting = "欢迎, ";
    $dashboardTranslate = "仪表板";
    $myAccountTranslate = "我的账户";
    $settingTranslate = "设置";
    $logOutTranslate = "登出";
} else if ($_SESSION['languageType'] == 'Japanese') {
    $greeting = "ようこそ, ";
    $dashboardTranslate = "ダッシュボード";
    $myAccountTranslate = "マイアカウント";
    $settingTranslate = "設定";
    $logOutTranslate = "ログアウト";
} else {
    // Default to English
    $greeting = "Welcome, ";
    $dashboardTranslate = "Dashboard";
    $myAccountTranslate = "My Account";
    $settingTranslate = "Setting";
    $logOutTranslate = "Log out";
}

////[Translation]/////////-->Bottom

?>
<div id="navigation">
    <div id='webTitle'><a href='../' id='homeLink'>Market Cost</a></div>
    <div id='navBar'>
        <div class='greeting'><?php echo $greeting . $firstName;?>!</div>
        <div class="dropDownSection">
            <button id='dropDownButton'><i class="fas fa-bars"></i></button>
            <div class="dropDownOptions">
                <div class='dropDownLinksDiv'><a class='dropDownLinks' href="../"><i class='fas fa-cloud-sun'></i> <?php echo $dashboardTranslate?></a></div>
                <div class='dropDownLinksDiv'><a class='dropDownLinks' href="/myAccount"><i class='fas fa-user-tie'></i> <?php echo $myAccountTranslate?></a></div>
                <div class='dropDownLinksDiv'><a class='dropDownLinks' href="/setting"><i class='fas fa-wrench'></i> <?php echo $settingTranslate?></a></div>
                <div class='dropDownLinksDiv logOutLink'><a class='dropDownLinks logOutLink' href="?logOut=true"><?php echo $logOutTranslate?><i class='bx bx-log-out' ></i> </a></div> 
            </div>
        </div>
        <div class='hidden'>
            <form id='logOutForm' action="" method='get'>
                <button type='submit' name='logOut'  class='logOutButton'><i class='bx bx-log-out' ></i></button>
            </form>
        </div>
                <?php
                    if (isset($_GET['logOut'])) {
                        // session_start();
                        $_SESSION = []; // Clear all session data
                        session_destroy(); // End the session
                        header("Location: ../secure");
                        exit();
                    }                    
                ?>
    </div>
 </div>
