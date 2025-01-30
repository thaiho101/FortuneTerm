<?php
if(session_status() == PHP_SESSION_NONE)
{
        session_start();
}
if(!isset($_SESSION['authenticated']) || !$_SESSION['authenticated'])
{
        header("Location: ../secure");
        exit();
}
require_once('../config.php');
require_once("../Components/language.php"); //Call function to activate the languageForm id
require_once("../Components/currency.php"); //Call function to activate the currencyForm id
require_once("../Components/theme.php"); //Call function to activate the themeForm id



?>

<!DOCTYPE html>



<html lang='en'>
<head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Market Cost</title>
        <?php //$theme was implemented in /Components/theme.php?>
        <link rel="stylesheet" href="<?php echo "../theme/" . $theme?>">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
        <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
        <link rel="icon" type="image/x-icon" href="/secure/shoppingCart2.png">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Lilita+One&display=swap" rel="stylesheet">
</head>
<body>
    <?php 
        require_once("../Components/navBar.php");
        ////[Translation]/////////-->Header
        if ($_SESSION['languageType'] == 'English') {
            $settingTranslate = "Setting";
            $languageTranslate = "Language";
            $currencyTranslate = "Currency";
            $themeTranslate = "Theme";
        } else if ($_SESSION['languageType'] == 'Vietnamese') {
            $settingTranslate = "Cài đặt";
            $languageTranslate = "Ngôn ngữ";
            $currencyTranslate = "Tiền tệ";
        } else if ($_SESSION['languageType'] == 'Spanish') {
            $settingTranslate = "Configuración";
            $languageTranslate = "Idioma";
            $currencyTranslate = "Moneda";
        } else if ($_SESSION['languageType'] == 'German') {
            $settingTranslate = "Einstellungen";
            $languageTranslate = "Sprache";
            $currencyTranslate = "Währung";
        } else if ($_SESSION['languageType'] == 'French') {
            $settingTranslate = "Paramètres";
            $languageTranslate = "Langue";
            $currencyTranslate = "Devise";
        } else if ($_SESSION['languageType'] == 'Korean') {
            $settingTranslate = "설정";
            $languageTranslate = "언어";
            $currencyTranslate = "통화";
        } else if ($_SESSION['languageType'] == 'Chinese') {
            $settingTranslate = "设置";
            $languageTranslate = "语言";
            $currencyTranslate = "货币";
        } else if ($_SESSION['languageType'] == 'Japanese') {
            $settingTranslate = "設定";
            $languageTranslate = "言語";
            $currencyTranslate = "通貨";
        } else {
            // Default to English if no match
            $settingTranslate = "Setting";
            $languageTranslate = "Language";
            $currencyTranslate = "Currency";
        }
        
        ////[Translation]/////////-->Bottom
    ?>
    <div id='settingBottom'>
        <div id='global-nav-setting'>
            <!-- ////// Global nav bars --Header-->
            <div id='global-nav-settingBar'>
                <form method='get' id='settingNavForm'>
                    <button type='submit' id='settingNav' class='' name='setting'><i class="fa fa-cog fa-spin"></i> <?php echo $settingTranslate;?></button>
                </form>          
            </div>
            <!-- ////// Global nav bars --Bottom-->
        </div>
        
        <div id='navContent'>
            <div id='languageSettingSection'>
                <label for=""><?php echo $languageTranslate;?>: </label>
                <form id='languageForm' method='post'>
                    <label for="languageType" > </label>
                    <select name="languageType" id="languageType" onchange='document.getElementById("languageForm").submit()'>
                        <option name='English' value="English" <?php echo ($_SESSION['languageType'] === "English") ? 'selected' : '' ?>>English</option>
                        <option name='Spanish' value="Spanish" <?php echo ($_SESSION['languageType'] === "Spanish") ? 'selected' : '' ?>>Spanish</option>
                        <option name='German' value="German" <?php echo ($_SESSION['languageType'] === "German") ? 'selected' : '' ?>>German</option>
                        <option name='French' value="French" <?php echo ($_SESSION['languageType'] === "French") ? 'selected' : '' ?>>French</option>
                        <option name='Korean' value="Korean" <?php echo ($_SESSION['languageType'] === "Korean") ? 'selected' : '' ?>>Korean</option>
                        <option name='Chinese' value="Chinese" <?php echo ($_SESSION['languageType'] === "Chinese") ? 'selected' : '' ?>>Chinese</option>
                        <option name='Japanese' value="Japanese" <?php echo ($_SESSION['languageType'] === "Japanese") ? 'selected' : '' ?>>Japanese</option>
                        <option name='Vietnamese' value="Vietnamese" <?php echo ($_SESSION['languageType'] === "Vietnamese") ? 'selected' : '' ?>>Vietnamese</option>
                    </select>
                </form>
            </div>
            <div id='currencySettingSection'>
                <label for=""><?php echo $currencyTranslate;?>: </label>
                <form id='currencyForm' method='post'>
                    <label for="currencyType"> </label>
                    <select name="currencyType" id="currencyType" onchange='document.getElementById("currencyForm").submit()'>
                        <option name='USD' value="USD" <?php echo ($_SESSION['currencyType'] === "USD") ? 'selected' : '' ?>>USD</option>
                        <option name='VND' value="VND" <?php echo ($_SESSION['currencyType'] === "VND") ? 'selected' : '' ?>>VND</option>
                    </select>
                </form>
            </div>
            <div id='themeSection'>
                <label for=""><?php echo "Theme";?>: </label>
                <form id='themeForm' method='post'>
                    <label for="themeType"> </label>
                    <select name="themeType" id="themeType" onchange='document.getElementById("themeForm").submit()'>
                        <option name='SkyLight' value="SkyLight" <?php echo ($_SESSION['themeType'] === "SkyLight") ? 'selected' : '' ?>>Sky Light (Default)</option>
                        <option name='PinkCharm' value="PinkCharm" <?php echo ($_SESSION['themeType'] === "PinkCharm") ? 'selected' : '' ?>>Pink Charm</option>
                        <option name='Midnight' value="Midnight" <?php echo ($_SESSION['themeType'] === "Midnight") ? 'selected' : '' ?>>Midnight</option>
                    </select>
                </form>
            </div>
        </div>
    </div>
    <script src="../script.js"></script>
<?php 
//Add div of Menu Navigation
require_once('../Components/menuNav.php');
$menu = $_SESSION['activeMenu'];
echo $menu;
?>
</body>
</html>