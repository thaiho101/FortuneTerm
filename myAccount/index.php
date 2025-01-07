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

?>


<html lang='en'>
<head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Market Cost</title>
        <link rel="stylesheet" href="../style.css">
        <!-- <link rel="stylesheet" href="/myProfile/style1.css"> -->
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
    <div id='accountBottom'>
        <div id='global-nav-myAccount'>
            <div id='global-nav-myProfile'>
                <?php 
                    $selectedNavMyProfile = '';
                    $myProfileHiddenClass = '';
                    $changePasswordHiddenClass = 'hidden';
                    if(($_SERVER['REQUEST_METHOD'] == 'GET') && (isset($_GET['myProfile'])))
                    {
                        $myProfileHiddenClass = '';
                        $selectedNavMyProfile = 'selectedNavMyProfile';
                        $changePasswordHiddenClass = 'hidden';
                    }
                ?>
                <form method='get'>
                    <button type='submit' id='myProfileNav' class='<?php echo $selectedNavMyProfile;?>' name='myProfile'>My Profile</button>
                </form>
                
            </div>
            <div id='global-nav-changePassword'>
                <?php 
                    $selectedNavChangePassword = '';
                    if(($_SERVER['REQUEST_METHOD'] == 'GET') && (isset($_GET['changePassword'])))
                    {
                        $changePasswordHiddenClass = '';
                        $myProfileHiddenClass = 'hidden';
                        $selectedNavChangePassword = 'selectedNavChangePassword';
                    }
                ?>
                <form method='get'>
                    <button type='submit' id='changePasswordNav' class='<?php echo $selectedNavChangePassword;?>' name='changePassword'>Change Password</button>
                </form>
            </div>
        </div>
        <!-- ////// myProfile Content --Header-->
        <div id='myProfileContent' class="<?php echo $myProfileHiddenClass; ?>">
            <div id='userPicture'><img src="/myAccount/userPicture.jpg" alt="Avatar"></div>
            <div id='firstLastNameSection'>
                <div id='firstNameSection'>
                    <div id='firstName'>First Name: </div>
                    <div>
                        <table border='1' id='tableok'><tr id='firstNameTR'><td class='tdWidth'><?php echo $firstName;?></td></tr></table>
                    </div>
                </div>
                <div id='lastNameSection'>
                    <div>Last Name: </div>
                    <div>
                        <table border='1'><tr class='a'><td class='tdWidth'><?php echo $lastName;?></td></tr></table>
                    </div>
                </div>
            </div>
            <div id='emailSection'>
                <div>Email: </div>
                <div>
                    <table border='1'><td class='tdWidthEmail'><?php echo $email;?></td></table>
                </div>
            </div>
        </div>
        <!-- ////// myProfile Content --Bottom-->

        <!-- ////// changePassword Content --Header-->
        <div id='changePasswordContent' class="<?php echo $changePasswordHiddenClass; ?>">Hello</div>
        <!-- ////// changePassword Content --Bottom-->
        
    </div>
</body>

</html>
