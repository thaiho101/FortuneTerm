<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Market Cost</title>
    <link rel="stylesheet" href="/register/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="/register/shoppingCart2.png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lilita+One&display=swap" rel="stylesheet">
</head>

<body id='wholePage'>
    <div id='navBar'><a href='../' id='homeLink'>Market Cost</a></div>
    <div id='registerParent'><div id='registerPanel'>
        <form action="" method='post'>
            <div id='registerLabel'>Sign Up</div>
            <div id='firstNameSection'>
                <div class='spaceBetween'><label for='firstName' class='nameLabel'>First Name</label></div>
                <div><input type='text' name='firstName' id='firstName' placeholder="Enter your first name" class='inputBox' required></div>
            </div>
            <div id='lastNameSection'>
                <div class='spaceBetween'>
                    <label for='lastName' class='nameLabel'>Last Name</label>
                </div>
                <div>
                    <input type='text' name='lastName' id='lastName' placeholder="Enter your last name" class='inputBox' required>
                </div>
            </div>
            <div id='emailSection'>
                <div class='spaceBetween emailLabel'>
                    <label for='email' class='nameLabel emailLabelPadRight'>Email</label>
                </div>
                <div>
                    <input type='email' name='email' id='email' placeholder="Enter your email" class='inputBox' required>
                </div>
                
            </div>
            <div id='passwordSection'>
                <div class='spaceBetween'>
                    <label for='password' class='nameLabel passwordLabelPadRight'>Password</label>
                </div>
                <div>
                    <input type='password' name='password' id='password' placeholder="Enter your password" class='inputBox' minlength="6" required>
                </div>
            </div>
            <div id='confirmPasswordSection'>
                <div class='spaceBetween'>
                    <label for='password' class='nameLabel confirmLabelPadRight'>Confirm</label>
                </div>
                <div>
                    <input type='password' name='confirmPassword' id='confirmPassword' placeholder="Reenter the password" class='inputBox' minlength="6" required>
                </div>
            </div>
            <div id='signUpSection'>
                <button type='submit' name='signUp' id='signUpButton'>Submit</button>
            </div>
            <div id='errorMessage'></div>
        </form>
    </div></div>

<?php
require_once('../config.php');
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($conn->connect_error)
{
        die("Connect failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['signUp'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $firstName = $_POST['firstName'];
        $lastName = $_POST['lastName'];

        $confirmPassword = $_POST['confirmPassword'];

        if ($password === $confirmPassword)
        {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Secure password hashing

            $insertQuery = "INSERT INTO users (email, password, first_name, last_name) VALUES (?, ?, ?, ?)";
            $statement = $conn->prepare($insertQuery);
    
            if ($statement) {
                $statement->bind_param('ssss', $email, $hashedPassword, $firstName, $lastName);
    
                if ($statement->execute()) {
                    header("Location: " . $_SERVER['PHP_SELF']);
                    header("Location: ../secure");
                    exit();
                } else {
                    echo "<div class='error'>Error: " . $conn->error . "</div>";
                }
                $statement->close();
            } else {
                echo "<div class='error'>Error preparing statement: " . $conn->error . "</div>";
            }
        } else {
            // echo "<script src='regScript.js'></script>";
            echo "<script>
                document.getElementById('errorMessage').innerHTML = `<div><i class='fas fa-exclamation-triangle'></i> Passwords do not match!</div>`;
                document.getElementById('errorMessage').style.backgroundColor = 'white';
                document.getElementById('errorMessage').style.color = 'red';
                document.getElementById('errorMessage').style.textAlign = 'center';
                document.getElementById('errorMessage').style.paddingTop = '10px';
                document.getElementById('errorMessage').style.borderBottomLeftRadius = '8px';
                document.getElementById('errorMessage').style.borderBottomRightRadius = '8px';
                document.getElementById('errorMessage').style.borderTopLeftRadius = '8px';
                document.getElementById('errorMessage').style.borderTopRightRadius = '8px';
                document.getElementById('errorMessage').style.jutifyContent = 'center';
                document.getElementById('errorMessage').style.alignItems = 'center';
                document.getElementById('errorMessage').style.contentItems = 'center';
                document.getElementById('errorMessage').style.height = '30px';
                document.getElementById('errorMessage').style.backgroundImage = 'linear-gradient(white, yellow, white)';
            </script>";
        }
        
    }
}
?>

</body>
</html>
