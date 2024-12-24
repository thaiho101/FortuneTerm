<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset="UTF-8">
    <title>Market Cost</title>
    <link rel="stylesheet" href="/register/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="/Icon/Moneybag.png">
</head>

<body id='wholePage'>
    <div id='registerPanel'>
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
                    <label for='email' class='nameLabel'>Email</label>
                </div>
                <div>
                    <input type='email' name='email' id='email' placeholder="Enter your email" class='inputBox' required>
                </div>
                
            </div>
            <div id='passwordSection'>
                <div class='spaceBetween'>
                    <label for='password' class='nameLabel'>Password</label>
                </div>
                <div>
                    <input type='password' name='password' id='password' placeholder="Enter your password" class='inputBox' minlength="6" required>
                </div>
            </div>
            <div id='signUpSection'>
                <button type='submit' name='signUp' id='signUpButton'>Sign up</button>
            </div>
        </form>
    </div>

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
    }
}
?>

</body>
</html>
