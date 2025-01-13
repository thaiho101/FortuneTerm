<?php
// require_once("../config.php"); 
$userId = $_SESSION['user_id']; 
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($conn->connect_error)
{
        die("Connect failed: " . $conn->connect_error);
}
////[Currency Type]/////////-->Header
//Update currency selected into users database
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['currencyType']))
{
    $currencyType = $_POST['currencyType'];
    $_SESSION['currencyType'] = $currencyType; // Save in session for persistence

    $sql = "UPDATE users SET preferred_currency = ? 
            WHERE user_id = ? ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $currencyType, $userId);
    $stmt->execute();
    $stmt->close();
}

//Invoke the preferred currency from users database
$sql = "SELECT preferred_currency FROM users
        WHERE user_id = ? ";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $userId);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc())
{
    $_SESSION['currencyType'] = $row['preferred_currency'];
} else {
    $_SESSION['currencyType'] = 'USD'; //Default currency
}
$stmt->close();

$currencyType = $_SESSION['currencyType']; //Set the current currency activated from data
//Set currency Symbol
if ($currencyType === 'USD') {
    $currencySymbol = "$";
} else if ($currencyType === 'VND') {
    $currencySymbol = "VND";
}

//Currency function
class CurrencyFormatter {
    public static function format($number, $currencyType) {
        switch ($currencyType) {
            case 'USD':
                return '' . number_format($number, 2);
            case 'VND':
                return '' . number_format($number, 0, '', '.');
            default:
                return number_format($number, 2);
        }
    }
}
////[Currency Type]/////////-->Bottom
?>

<!-- <form id='currencyForm' method='post'>
    <label for="currencyType"> </label>
    <select name="currencyType" id="currencyType" onchange='document.getElementById("currencyForm").submit()'>
        <option name='USD' value="USD" <?php echo ($_SESSION['currencyType'] === "USD") ? 'selected' : '' ?>>USD</option>
        <option name='VND' value="VND" <?php echo ($_SESSION['currencyType'] === "VND") ? 'selected' : '' ?>>VND</option>
    </select>
</form> -->