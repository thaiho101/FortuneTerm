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
                <?php 
                    $selectedNavMyProfile = 'selectedNavMyProfile';
                    $myProfileHiddenClass = '';
                    $changePasswordHiddenClass = 'hidden';
                    if(($_SERVER['REQUEST_METHOD'] == 'GET') && (isset($_GET['myProfile'])))
                    {
                        $myProfileHiddenClass = '';
                        $selectedNavMyProfile = 'selectedNavMyProfile';
                        $changePasswordHiddenClass = 'hidden';
                    }
                    $selectedNavChangePassword = '';
                    if(($_SERVER['REQUEST_METHOD'] == 'GET') && (isset($_GET['changePassword'])))
                    {
                        $changePasswordHiddenClass = '';
                        $myProfileHiddenClass = 'hidden';
                        $selectedNavChangePassword = 'selectedNavChangePassword';
                        $selectedNavMyProfile = '';
                    }
                    ////// Change password button --Header-->
                    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['changePasswordButton']))
                    {
                        $changePasswordHiddenClass = '';
                        $myProfileHiddenClass = 'hidden';
                        $selectedNavChangePassword = 'selectedNavChangePassword';
                        $selectedNavMyProfile = '';
                    }
                    ////// Change password button --Bottom-->
                ?>
            <!-- ////// Global nav bars --Header-->
            <div id='global-nav-myProfile'>
                <form method='get'>
                    <button type='submit' id='myProfileNav' class='<?php echo $selectedNavMyProfile;?>' name='myProfile'><i class='fas fa-id-badge'></i> My Profile</button>
                </form>          
            </div>
            <div id='global-nav-changePassword'>
                <form method='get'>
                    <button type='submit' id='changePasswordNav' class='<?php echo $selectedNavChangePassword;?>' name='changePassword'> <i class='fas fa-shield-alt'></i> Change Password</button>
                </form>
            </div>
            <!-- ////// Global nav bars --Bottom-->
        </div>
        
        <div id='navContent'>
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
            <div id='changePasswordContent' class="<?php echo $changePasswordHiddenClass; ?>">
                <div id='changePasswordTitle'><i class='fas fa-shield-alt'></i> Change Password</div>
                <div id='changePasswordNotice'>This password must be at least 6 characters long.</div>
                <form method='post' id='changePasswordForm'>  
                    <div id='emailChangePasswordSection'>
                        <label for='email'>Email</label>
                        <div class='emailDataDiv'><?php echo $email;?></div>
                    </div>
                    <div id='currentChangePassword'>
                        <label for='currentPassword'>Current Password</label>
                        <div class="password-wrapper">
                            <input type='password' id='currentPassword' class='passwordInput' name='currentPassword' minlength="6" required></input>
                            <button type="button" class='visiblePasswordButton' tabindex="-1" onclick="togglePasswordVisibility('currentPassword', this)">👁️</button>
                        </div>
                    </div>
                    <div id='newChangePassword'>
                        <label for='newPassword'>New Password</label>
                        <div class="password-wrapper">
                            <input type='password' id='newPassword' class='passwordInput' name='newPassword' minlength="6" required></input>
                            <button type="button" class='visiblePasswordButton' tabindex="-1" onclick="togglePasswordVisibility('newPassword', this)">👁️</button>
                        </div>
                    </div>
                    <div id='confirmChangePassword'>
                        <label>Confirm Password</label>
                        <div class="password-wrapper">
                            <input type='password' id='confirmPassword' class='passwordInput' name='confirmPassword' minlength="6" required></input>
                            <button type="button" class='visiblePasswordButton' tabindex="-1" onclick="togglePasswordVisibility('confirmPassword', this)">👁️</button>
                        </div>
                    </div>
                    <div id='changePasswordSubmit'>
                        <button type="submit" name='changePasswordButton' id='changeButton'>Change</button>
                    </div>
                </form>
                <?php 
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['changePasswordButton'])) {
                    $currentPassword = $_POST['currentPassword'];
                    $newPassword = $_POST['newPassword'];
                    $confirmPassword = $_POST['confirmPassword'];

                    // Retrieve the original password
                    $stmt = $conn->prepare("SELECT password FROM users WHERE user_id = ? AND deleted = 'N'");
                    $stmt->bind_param('i', $userId);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($row = $result->fetch_assoc()) {
                        $originPassword = $row['password'];
                        if (password_verify($currentPassword, $originPassword)) {
                            if ($newPassword === $confirmPassword) {
                                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                                $updateStmt = $conn->prepare("UPDATE users SET password = ? WHERE user_id = ? AND deleted = 'N'");
                                $updateStmt->bind_param('si', $hashedPassword, $userId);
                                if ($updateStmt->execute()) {
                                    echo "<div class='success'>Password successfully updated!</div>";
                                } else {
                                    echo "<div class='error'>Error updating password: " . htmlspecialchars($conn->error) . "</div>";
                                }
                                $updateStmt->close();
                            } else {
                                //Need to be fixed with direct link!!!!!!!!!!!!!!
                                echo "<div class='error'>New passwords or Confirm password do not match!</div>";
                            }
                        } else {
                            echo "<div class='error'>Incorrect current password!</div>";
                        }
                    } else {
                        echo "<div class='error'>User not found!</div>";
                    }
                    $stmt->close();
                }
                ?>
            </div>
            <!-- ////// changePassword Content --Bottom-->
        
        </div>
    </div>
    <script src="../script.js"></script>
</body>

</html>
