<!DOCTYPE html>
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

?>


<html lang='en'>
<head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Market Cost</title>
        <link rel="stylesheet" href="../style.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
        <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
        <!-- <link rel="icon" type="image/x-icon" href="/Icon/Moneybag.png"> -->
        <link rel="icon" type="image/x-icon" href="/secure/shoppingCart2.png">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Lilita+One&display=swap" rel="stylesheet">
</head>
<body>
    <?php require_once("../Components/navBar.php");?>
    <div id='settingBottom'>
        <div id='global-nav-setting'>
            <!-- ////// Global nav bars --Header-->
            <div id='global-nav-settingBar'>
                <form method='get' id='settingNavForm'>
                    <button type='submit' id='settingNav' class='' name='setting'><i class="fa fa-cog fa-spin"></i> Setting</button>
                </form>          
            </div>
            <!-- ////// Global nav bars --Bottom-->
        </div>
        
        <div id='navContent'>
            <div id='languageSettingSection'>
                <label for="">Language: </label>
                <form id='languageForm' method='post'>
                    <label for="languageType" > </label>
                    <select name="languageType" id="languageType" onchange='document.getElementById("languageForm").submit()'>
                        <option name='English' value="English" <?php echo ($_SESSION['languageType'] === "English") ? 'selected' : '' ?>>English</option>
                        <option name='Vietnamese' value="Vietnamese" <?php echo ($_SESSION['languageType'] === "Vietnamese") ? 'selected' : '' ?>>Vietnamese</option>
                    </select>
                </form>
            </div>
            <div id='currencySettingSection'>
                <label for="">Currency: </label>
                <form id='currencyForm' method='post'>
                    <label for="currencyType"> </label>
                    <select name="currencyType" id="currencyType" onchange='document.getElementById("currencyForm").submit()'>
                        <option name='USD' value="USD" <?php echo ($_SESSION['currencyType'] === "USD") ? 'selected' : '' ?>>USD</option>
                        <option name='VND' value="VND" <?php echo ($_SESSION['currencyType'] === "VND") ? 'selected' : '' ?>>VND</option>
                    </select>
                </form>
            </div>
        </div>
    </div>
    <script src="../script.js"></script>
</body>
</html>