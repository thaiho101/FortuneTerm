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

if ($_SESSION['languageType'] == 'English')
{
    $greeting = "Welcome, ";
} else if ($_SESSION['languageType'] == 'Vietnamese') {
    $greeting = "Xin chào, ";
}

?>
<div id="navigation">
    <div id='webTitle'><a href='../' id='homeLink'>Market Cost</a></div>
    <div id='navBar'>
        <div class='greeting'><?php echo $greeting . $firstName;?>!</div>
        <div class="dropDownSection">
            <button id='dropDownButton'><i class="fas fa-bars"></i></button>
            <div class="dropDownOptions">
                <div class='dropDownLinksDiv'><a class='dropDownLinks' href="../"><i class='fas fa-cloud-sun'></i> Dashboard</a></div>
                <div class='dropDownLinksDiv'><a class='dropDownLinks' href="/myAccount"><i class='fas fa-user-tie'></i> My Account</a></div>
                <div class='dropDownLinksDiv'><a class='dropDownLinks' href="/setting"><i class='fas fa-wrench'></i> Setting</a></div>
                <div class='dropDownLinksDiv logOutLink'><a class='dropDownLinks logOutLink' href="?logOut=true">Log out <i class='bx bx-log-out' ></i> </a></div> 
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
