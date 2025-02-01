<?php
if(session_status() == PHP_SESSION_NONE)
{
        session_start();
        $_SESSION['activeMenu'] = "myAccount";
}
if(!isset($_SESSION['authenticated']) || !$_SESSION['authenticated'])
{
        header("Location: ../secure");
        exit();
}

require_once('../config.php');
require_once("../Components/language.php"); //Call function to activate the languageForm id
require_once("../Components/theme.php"); //Call function to activate the themeForm id
////[Translation]/////////-->Header
if ($_SESSION['languageType'] == 'English') {
    $myProfileTranslate = "My Profile";
    $firstNameTranslate = "First Name";
    $lastNameTranslate = "Last Name";

    $changePasswordTranslate = "Change Password";
    $regulationChangePasswordTranslate = "This password must be at least 6 characters long.";
    $currentPasswordTranslate = "Current Password";
    $newPasswordTranslate = "New Password";
    $confirmPasswordTranslate = "Confirm Password";

    $changeTranslate = "Change";
} else if ($_SESSION['languageType'] == 'Vietnamese') {
    $myProfileTranslate = "Tài khoản của tôi";
    $firstNameTranslate = "Tên";
    $lastNameTranslate = "Họ";

    $changePasswordTranslate = "Đổi Mật Khẩu";
    $regulationChangePasswordTranslate = "Mật khẩu của bạn phải có tối thiểu 6 ký tự.";
    $currentPasswordTranslate = "Mật khẩu đang sử dụng";
    $newPasswordTranslate = "Mật khẩu mới";
    $confirmPasswordTranslate = "Xác nhận mật khẩu";

    $changeTranslate = "Thay đổi";
} else if ($_SESSION['languageType'] == 'Spanish') {
    $myProfileTranslate = "Mi Perfil";
    $firstNameTranslate = "Nombre";
    $lastNameTranslate = "Apellido";

    $changePasswordTranslate = "Cambiar Contraseña";
    $regulationChangePasswordTranslate = "La contraseña debe tener al menos 6 caracteres.";
    $currentPasswordTranslate = "Contraseña Actual";
    $newPasswordTranslate = "Nueva Contraseña";
    $confirmPasswordTranslate = "Confirmar Contraseña";

    $changeTranslate = "Cambiar";
} else if ($_SESSION['languageType'] == 'German') {
    $myProfileTranslate = "Mein Profil";
    $firstNameTranslate = "Vorname";
    $lastNameTranslate = "Nachname";

    $changePasswordTranslate = "Passwort ändern";
    $regulationChangePasswordTranslate = "Dieses Passwort muss mindestens 6 Zeichen lang sein.";
    $currentPasswordTranslate = "Aktuelles Passwort";
    $newPasswordTranslate = "Neues Passwort";
    $confirmPasswordTranslate = "Passwort bestätigen";

    $changeTranslate = "Ändern";
} else if ($_SESSION['languageType'] == 'French') {
    $myProfileTranslate = "Mon Profil";
    $firstNameTranslate = "Prénom";
    $lastNameTranslate = "Nom";

    $changePasswordTranslate = "Changer le mot de passe";
    $regulationChangePasswordTranslate = "Ce mot de passe doit comporter au moins 6 caractères.";
    $currentPasswordTranslate = "Mot de passe actuel";
    $newPasswordTranslate = "Nouveau mot de passe";
    $confirmPasswordTranslate = "Confirmer le mot de passe";

    $changeTranslate = "Changer";
} else if ($_SESSION['languageType'] == 'Korean') {
    $myProfileTranslate = "내 프로필";
    $firstNameTranslate = "이름";
    $lastNameTranslate = "성";

    $changePasswordTranslate = "비밀번호 변경";
    $regulationChangePasswordTranslate = "비밀번호는 최소 6자 이상이어야 합니다.";
    $currentPasswordTranslate = "현재 비밀번호";
    $newPasswordTranslate = "새 비밀번호";
    $confirmPasswordTranslate = "비밀번호 확인";

    $changeTranslate = "변경";
} else if ($_SESSION['languageType'] == 'Chinese') {
    $myProfileTranslate = "我的资料";
    $firstNameTranslate = "名字";
    $lastNameTranslate = "姓";

    $changePasswordTranslate = "更改密码";
    $regulationChangePasswordTranslate = "密码至少需要6个字符。";
    $currentPasswordTranslate = "当前密码";
    $newPasswordTranslate = "新密码";
    $confirmPasswordTranslate = "确认密码";

    $changeTranslate = "更改";
} else if ($_SESSION['languageType'] == 'Japanese') {
    $myProfileTranslate = "マイプロフィール";
    $firstNameTranslate = "名";
    $lastNameTranslate = "姓";

    $changePasswordTranslate = "パスワードを変更する";
    $regulationChangePasswordTranslate = "パスワードは6文字以上である必要があります。";
    $currentPasswordTranslate = "現在のパスワード";
    $newPasswordTranslate = "新しいパスワード";
    $confirmPasswordTranslate = "パスワードを確認する";

    $changeTranslate = "変更";
} else {
    // Default to English
    $myProfileTranslate = "My Profile";
    $firstNameTranslate = "First Name";
    $lastNameTranslate = "Last Name";

    $changePasswordTranslate = "Change Password";
    $regulationChangePasswordTranslate = "This password must be at least 6 characters long.";
    $currentPasswordTranslate = "Current Password";
    $newPasswordTranslate = "New Password";
    $confirmPasswordTranslate = "Confirm Password";

    $changeTranslate = "Change";
}

////[Translation]/////////-->Bottom

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

                    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['changePasswordButton']))
                    {
                        $changePasswordHiddenClass = '';
                        $myProfileHiddenClass = 'hidden';
                        $selectedNavChangePassword = 'selectedNavChangePassword';
                        $selectedNavMyProfile = '';
                    }

                ?>

            <div id='global-nav-myProfile'>
                <form method='get'>
                    <button type='submit' id='myProfileNav' class='<?php echo $selectedNavMyProfile;?>' name='myProfile'><i class='fas fa-id-badge'></i> <?php echo $myProfileTranslate;?></button>
                </form>          
            </div>
            <div id='global-nav-changePassword'>
                <form method='get'>
                    <button type='submit' id='changePasswordNav' class='<?php echo $selectedNavChangePassword;?>' name='changePassword'> <i class='fas fa-shield-alt'></i> <?php echo $changePasswordTranslate;?></button>
                </form>
            </div>

        </div>
        
        <div id='navContent'>
            <!-- ////// myProfile Content --Header-->
            <div id='myProfileContent' class="<?php echo $myProfileHiddenClass; ?>">
                <div id='userPicture'><img src="/myAccount/userPicture.jpg" alt="Avatar"></div>
                <div id='firstLastNameSection'>
                    <div id='firstNameSection'>
                        <div id='firstName'><?php echo $firstNameTranslate;?>: </div>
                        <div>
                            <table border='1' id='tableok'><tr id='firstNameTR'><td class='tdWidth'><?php echo $firstName;?></td></tr></table>
                        </div>
                    </div>
                    <div id='lastNameSection'>
                        <div><?php echo $lastNameTranslate;?>: </div>
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
                <div id='changePasswordTitle'><i class='fas fa-shield-alt'></i> <?php echo $changePasswordTranslate;?></div>
                <div id='changePasswordNotice'><?php echo $regulationChangePasswordTranslate;?></div>
                <form method='post' id='changePasswordForm'>  
                    <div id='emailChangePasswordSection'>
                        <label for='email'>Email</label>
                        <div class='emailDataDiv'><?php echo $email;?></div>
                    </div>
                    <div id='currentChangePassword'>
                        <label for='currentPassword'><?php echo $currentPasswordTranslate;?></label>
                        <div class="password-wrapper">
                            <input type='password' id='currentPassword' class='passwordInput' name='currentPassword' minlength="6" required></input>
                            <button type="button" class='visiblePasswordButton' tabindex="-1" onclick="togglePasswordVisibility('currentPassword', this)">👁️</button>
                        </div>
                    </div>
                    <div id='newChangePassword'>
                        <label for='newPassword'><?php echo $newPasswordTranslate;?></label>
                        <div class="password-wrapper">
                            <input type='password' id='newPassword' class='passwordInput' name='newPassword' minlength="6" required></input>
                            <button type="button" class='visiblePasswordButton' tabindex="-1" onclick="togglePasswordVisibility('newPassword', this)">👁️</button>
                        </div>
                    </div>
                    <div id='confirmChangePassword'>
                        <label><?php echo $confirmPasswordTranslate;?></label>
                        <div class="password-wrapper">
                            <input type='password' id='confirmPassword' class='passwordInput' name='confirmPassword' minlength="6" required></input>
                            <button type="button" class='visiblePasswordButton' tabindex="-1" onclick="togglePasswordVisibility('confirmPassword', this)">👁️</button>
                        </div>
                    </div>
                    <div id='changePasswordSubmit'>
                        <button type="submit" name='changePasswordButton' id='changeButton'><?php echo $changeTranslate;?></button>
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
                                    echo "<div class='success'>&#9989; Password successfully updated!</div>";
                                } else {
                                    echo "<div class='error'>Error updating password: " . htmlspecialchars($conn->error) . "</div>";
                                }
                                $updateStmt->close();
                            } else {
                                echo "<div class='error'><i class='fas fa-exclamation-triangle'></i>New passwords or Confirm password do not match!</div>";
                            }
                        } else {
                            echo "<div class='error'><i class='fas fa-exclamation-triangle'></i>Incorrect current password!</div>";
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
<?php 
//Add div of Menu Navigation
require_once('../Components/menuNav.php');
?>
</body>

</html>
