<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if(!isset($_SESSION['authenticated']) || !$_SESSION['authenticated'])
{
        header("Location: /secure");
        exit();
}
// require_once("index.php"); 
$userId = $_SESSION['user_id'];


// Set Cache-Control to no-cache, no-store, must-revalidate
header("Cache-Control: no-cache, no-store, must-revalidate");
// Set Pragma to no-cache (for HTTP/1.0 backward compatibility)
header("Pragma: no-cache");
// Set Expires to a past date to invalidate the cache
header("Expires: 0");

require_once("config.php"); 
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

////[Translation]/////////-->Header
if ($_SESSION['currencyType'] == 'USD')
{
    $greeting = "Welcome, ";
    $newTransaction = "New Transaction";
    $dateTranslate = "Date";
    $marketTranslate = "Market";
    $foodAndBeverageCostTranslate = "Food & Beverage Cost";
    $otherCostTranslate = "Other Cost";
    $insertTranslate = "Insert";
    $haveSetButtonNotificationTranslate = "You have set the budget for this month is: ";
    $haveNotSetBudgetTranslate = "You have not set the budget for this month! ";
    $setBudgetTranslate = "Set Budget";
    $showBudgetTranslate = "Show Budget";
    $yourBudgetSummaryTranslate = "Your Budget Summary";
    $yearTranslate = "Year";
    $monthTranslate = "Month";
    $budgetTranslate = "Budget";
    $totalBudgetTranslate = "Total Budget";
    $closeTranslate = "Close";
    $dayOfWeekTranslate = "Day of Week";
    $totalCostTranslate = "Total Cost";
    $balanceForShoppingTranslate = "Balance for Shopping";
    $mostVisitedMarkets = "Most Visited Markets";
    $marketCostDistribution = "Market Cost Distribution";
    $foodAndBeverageTranslate = "Food & Beverage";
    $otherTranslate = "Other";
} else if ($_SESSION['currencyType'] == 'VND') {
    $greeting = "Xin chào, ";
    $newTransaction = "Giao Dịch Mới";
    $dateTranslate = "Ngày";
    $marketTranslate = "Chợ";
    $foodAndBeverageCostTranslate = "Chi phí Đồ ăn & Thức Uống";
    $otherCostTranslate = "Chi phí khác";
    $insertTranslate = "Thêm";
    $haveSetButtonNotificationTranslate = "Ngân sách bạn đã thiết lập cho tháng này là: ";
    $haveNotSetBudgetTranslate = "Bạn chưa thiết lập ngân sách cho tháng này! ";
    $setBudgetTranslate = "Đặt ngân sách";
    $showBudgetTranslate = "Hiển thị ngân sách";
    $yourBudgetSummaryTranslate = "Tổng quan ngân sách của bạn";
    $yearTranslate = "Năm";
    $monthTranslate = "Tháng";
    $budgetTranslate = "Ngân sách";
    $totalBudgetTranslate = "Tổng Ngân sách";
    $closeTranslate = "Đóng";
    $dayOfWeekTranslate = "Thứ";
    $totalCostTranslate = "Tổng Chi Phí";
    $balanceForShoppingTranslate = "Số Tiền Mua Sắm Còn Lại";
    $mostVisitedMarkets = "Các Chợ Được Ghé Thăm Nhiều Nhất";
    $marketCostDistribution = "Phân Bổ Chi Phí";
    $foodAndBeverageTranslate = "Đồ ăn & Thức uống";
    $otherTranslate = "Khác";
}
////[Translation]/////////-->Bottom

$stmt = $conn->prepare("SELECT first_name, last_name FROM users WHERE user_id = ?");
$stmt->bind_param('i', $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $firstName = $row['first_name'];
} else {
    $firstName = "Guest";
}
$stmt->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Market Cost</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="shoppingCart2.png">

    <script src="https://www.gstatic.com/charts/loader.js"></script>


    <!-- font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lilita+One&display=swap" rel="stylesheet">
</head>
<body>
    <?php require_once("./Components/navBar.php"); ?>

    <div id="bottom">
        <div id="dataChange">
            <div class="insert">
                <div class='newTransactionLabel'>
                    <a class='newTransactionStyle'  onclick="focusOnDate()"><?php echo $newTransaction;?></a>
                </div>
                
                <form method='post' id='insertionForm'>
                    <div class='form-row'>
                        <button for='date' class='insertLabel dateLabel' ><?php echo $dateTranslate;?></button>
                        <input type='date' name='date' id='date' class='insertBox inserBoxDate' required>
                    </div>

                    <div class='form-row'>
                        <button for='store' class='insertLabel interface'><i class="fas fa-store"></i><?php echo " " . $marketTranslate;?></button>

                        <input list="store" name='store' class='insertBox' required>
                        <datalist id="store">
<?php
    $sql = "SELECT distinct market_name
    FROM market_cost
    WHERE user_id = ?
    ORDER BY market_name ASC";

    $statement = $conn->prepare($sql);
    $statement->bind_param('i', $userId);
    $statement->execute();
    $result = $statement->get_result();

    // $result = $conn->query($sql);

    if($result->num_rows > 0)
    {
        while($row = $result->fetch_assoc())
        {
            echo "<option value='" . $row['market_name'] . "'></option>";
        }
    }
    $statement->close();
?>
                        </datalist>
                    </div>

                    <div class='form-row'>
                        <button for='fbCost' class='insertLabel '><?php echo $foodAndBeverageCostTranslate;?></button>
                        <input type='number' name='fbCost' id='fbCost' placeholder="<?php echo $currencySymbol;?>" class='insertBox insertBoxFBCost' step="0.01">
                    </div>

                    <div class='form-row'>
                        <button for='otherCost' class='insertLabel'><?php echo $otherCostTranslate;?></button>
                        <input type='number' name='otherCost' id='otherCost' placeholder="<?php echo $currencySymbol;?>" class='insertBox insertBoxOtherCost' step="0.01">
                    </div>

                    <div class='insertButtonDiv'>
                        <button type='submit' name='insert' class='insertButton'><?php echo $insertTranslate;?></button>
                    </div>
                </form>
            </div>
<?php
if($_SERVER['REQUEST_METHOD'] == 'POST')
{
    if(isset($_POST['insert']))
    {
        // $userId on the top of the file
        $visitDate = $_POST['date'];
        $marketName = $_POST['store']; //Need to convert marketName into market_id to push into market_cost table
        // $FBCost = $_POST['fbCost'] == '' ? 0 : $_POST['fbCost'];
        // $otherCost = $_POST['otherCost'] == '' ? 0 : $_POST['otherCost'];
        $FBCost = $_POST['fbCost'];
            if ($FBCost == '')
            {
                    $FBCost = 0;
            }
        $otherCost = $_POST['otherCost'];
            if ($otherCost == '')
            {
                    $otherCost = 0;
            }

        if (is_numeric($FBCost) && is_numeric($otherCost))
        {
            $insertQuery = "INSERT INTO market_cost (user_id, visit_date, market_name, food_bev_cost, other_cost) 
            VALUES (?, ?, ?, ?, ?)";
            $statement = $conn->prepare($insertQuery);
            $statement->bind_param('issdd', $userId, $visitDate, $marketName, $FBCost, $otherCost);
            $statement->execute();
            $statement->close();

            header("Location: " . $_SERVER['PHP_SELF']);
            exit(); 
        } else {
            header("Location: " . "/warning");
            exit(); 
        }
    }
}
?>
<?php
$sql = "SELECT DATE_FORMAT(visit_date, '%Y') AS YEAR, DATE_FORMAT(visit_date, '%m') AS MONTH
        FROM market_cost
        WHERE user_id = ?
        AND deleted = 'N'
        ORDER BY visit_date DESC
        LIMIT 1";

$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $userId);
$stmt->execute();
$result = $stmt->get_result();

$latestTransactionYear = date('Y');
$latestTransactionMonth = date('m');
if($result->num_rows > 0)
{
    $row = $result->fetch_assoc();
    $latestTransactionYear = $row['YEAR'];
    $latestTransactionMonth = $row['MONTH'];
}
$stmt->close();
// Set default year and month to current values if not provided
// $yearSelected = isset($_GET['year']) ? $_GET['year'] : date('Y');
// $monthSelected = isset($_GET['month']) ? $_GET['month'] : date('m');

$yearSelected = isset($_GET['year']) ? $_GET['year'] : $latestTransactionYear;
$monthSelected = isset($_GET['month']) ? $_GET['month'] : $latestTransactionMonth;
?>

            <div class="budget">
                <div class='budgetNotice'>
                    <div class='budgetNotification'>
                        <?php 
                            $sql = "SELECT amount
                            FROM budget
                            WHERE user_id = ?
                            AND year = ?
                            AND month = ?";

                            $stmt = $conn->prepare($sql);
                            $stmt->bind_param('iss', $userId, $yearSelected, $monthSelected);
                            $stmt->execute();
                            $result = $stmt->get_result();
    
                            // $result = $conn->query($sql);
                            // $amoutBool =
                            $currentBudget = NULL;
                            if($result->num_rows > 0)
                            {
                                $row = $result->fetch_assoc();
                                $currentBudget = $row['amount'];
                                echo $haveSetButtonNotificationTranslate . "<div id='currentBudgetSetSection'><div class='currentSetBudgetColor'>" . $currencySymbol . " " . CurrencyFormatter::format($currentBudget, $currencyType) . " &#9989;</div>
                                                                                        </div>";
                            } else {
                                echo $haveNotSetBudgetTranslate;
                                echo "<div id='messageReminder'><i class='fas fa-exclamation-triangle'></i></div>";
                            }

                            $stmt->close();
                        ?>
                        
                    </div>
                    <input type="text" id='userInfo' class='hidden' value='<?php echo $userId ?>'>
                    <input type="text" id='currentBudget' class='hidden' value='<?php echo $currentBudget ?>'>

                </div>
                <div class='newTransactionLabel'>
<?php 
if($yearSelected == '' || $monthSelected == '')
{
    $hiddenClass = 'hidden';
}
                echo "<button class='setBudgetButton $hiddenClass' onclick='setBudget()'>" . $setBudgetTranslate . "</button>"
?>
                    <!-- <button class='setBudgetButton' onclick="setBudget()">Set Budget</button> -->
                </div>
                <div class='newTransactionLabel'>
                    <form method='post'>
                        <button id='openModal' type='button' name='showBudget' class='showBudgetButton' onclick="document.getElementById('showBudgetModal').showModal()"><?php echo $showBudgetTranslate;?></button>
                    </form>
                    <dialog id='showBudgetModal'>
                            <h2 id='budgetSummaryTitle'><?php echo $yourBudgetSummaryTranslate;?></h2>
                            <div id='showBudgetContent'>
<?php
                        if ($yearSelected == '')
                        {
                            echo "The [Year] filter must be specified.";
                        }
                        $sql = "SELECT year, month, amount
                            FROM budget
                            WHERE user_id = ?
                            AND year = ?
                            ";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param('is', $userId, $yearSelected);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        if($result->num_rows > 0)
                        {
                            echo "<table border='1'>";
                            echo "<th class='titleBackgroundShowBudget yearWidth'>" . $yearTranslate . "</th>
                                    <th class='titleBackgroundShowBudget monthWidth'>" . $monthTranslate . "</th>
                                    <th class='titleBackgroundShowBudget'>" . $budgetTranslate . "</th>";
                            
                            $dataLoop = [];

                            while($row = $result->fetch_assoc())
                            {
                                $dataLoop[$row['month']] = $row;
                            }
                            
                            $totalBudget = 0;
                            for ($monthBudget = 1; $monthBudget <= 12; $monthBudget++)
                            {
                                if (isset($dataLoop[$monthBudget]))
                                {
                                    $row = $dataLoop[$monthBudget];
                                    echo "<tr class='budgetSummaryAlign'>
                                    <td class='budgetSymmaryWidth'>" . $row['year'] . "</td>
                                    <td class='budgetSymmaryWidth monthWidth'>" . $monthBudget . "</td>
                                    <td class='budgetSymmaryWidth blueBudgetSet'>" . $currencySymbol . " " . CurrencyFormatter::format($row['amount'], $currencyType) . " &#9989</td>
                                    </tr>";
                                    $totalBudget += $row['amount'];
                                } else {
                                    echo "<tr class='budgetSummaryAlign'>
                                    <td class='budgetSymmaryWidth'>" . $yearSelected . "</td>
                                    <td class='budgetSymmaryWidth monthWidth'>" . $monthBudget . "</td>
                                    <td class='budgetSymmaryWidth awaitingInput'>Awaiting input &#8644;</td>
                                    </tr>";
                                }
                            }
                            echo "<tr class='budgetSummaryAlign titleBackgroundShowBudget'><td>{$row['year']}</td>
                                    <td class='titleBackgroundShowBudget'>" . $totalBudgetTranslate . "</td>
                                    <td class='blueBudgetSet titleBackgroundShowBudget'>" . $currencySymbol . " " . CurrencyFormatter::format($totalBudget, $currencyType) . "</td></tr>";
                            echo "</table>";
                        }
?>
</div>
                            <div id='closeButtonShowBudgetSection'>
                                <button id='closeButtonShowBudget' onclick="document.getElementById('showBudgetModal').close()"><?php echo $closeTranslate;?></button>
                            </div>
                    </dialog>
                </div>
            </div>
        </div>

        <div id="dataAnalyze">
            <div id='filter' class="filter">
                <form id='currencyForm' method='post'>
                    <label for="currencyType"> </label>
                    <select name="currencyType" id="currencyType" onchange='document.getElementById("currencyForm").submit()'>
                        <option name='USD' value="USD" <?php echo ($_SESSION['currencyType'] === "USD") ? 'selected' : '' ?>>USD</option>
                        <option name='VND' value="VND" <?php echo ($_SESSION['currencyType'] === "VND") ? 'selected' : '' ?>>VND</option>
                    </select>
                </form>
                <form method='get' class='yearMonthDiv'>
                    <label for="year" class='yearMonthFilterStyle'><i class="fa fa-cog fa-spin"></i> <?php echo $yearTranslate;?>:  </label>
                        <select name="year" class='yearOptionStyle'id='year'>
                    <?php
                        $sql = "SELECT distinct DATE_FORMAT(visit_date, '%Y') AS year
                                FROM market_cost
                                WHERE user_id = ?
                                AND deleted = 'N'
                                ORDER BY year DESC";

                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param('i', $userId);
                        $stmt->execute();
                        $result = $stmt->get_result();
////[Year Selected]//////////-->Header
                        if($result->num_rows > 0)
                        {
                            // echo "<option value='" . date('Y') . "'>" . date('Y') . "</option>";
                            while($row = $result->fetch_assoc())
                            {
                                if ($row['year'] == $yearSelected) {
                                    echo "<option value='" . $row['year'] . "' selected >" . $row['year'] . "</option>";
                                } else {
                                    echo "<option value='" . $row['year'] . "'>" . $row['year'] . "</option>";
                                }
                            }

                            $allYearSelected = ($yearSelected == '') ? 'selected' : '';
                            echo "<option value='' $allYearSelected >All Years</option>"; 
                        }
////[Year Selected]/////////-->Bottom
                    ?>
                        </select>
                    <label for="month" class='yearMonthFilterStyle'><i class="fa fa-cog fa-spin"></i> <?php echo $monthTranslate;?>: </label>
                        <select name="month" class='monthOptionStyle' id='month'>
                            <?php
////[Month Selected]/////////-->Header
                                for ($i = 1; $i < 13; $i++)
                                {
                                    $monthModified = '';
                                    if ($i < 10)
                                    {
                                        $monthModified = "0" . $i;
                                    } else {
                                        $monthModified = $i;
                                    }

                                    if ($i == $monthSelected)
                                    {
                                        echo "<option value='" . $monthModified . "' selected >" . "" . $monthModified . "</option>";
                                    } else {
                                        echo "<option value='" . $monthModified . "'>" . "" . $monthModified . "</option>";
                                    }

                                    
                                };

                                $allMonthSelected = ($monthSelected == '') ? 'selected' : '';
                                echo "<option value='' $allMonthSelected>All Months</option>"; 
////[Month Selected]/////////-->Bottom
                            ?>
                        </select>
                        <button type="submit" name='filter' class='filterButton'><i class="fa fa-filter"></i></button>
                </form>
            </div>
<?php

?>
            <div class="header">
                <table class='headerTable'>
                    <thead id='contentHeader'>
                        <tr class='headerRow'>
                            <th class='fontStyle expand wrapText tdLength'><?php echo $dayOfWeekTranslate;?></th>
                            <th class='fontStyle expand tdLength'><?php echo $dateTranslate;?></th>
                            <th class='fontStyle expand tdLength'><?php echo $marketTranslate;?></th>
                            <th class='fontStyle expand wrapText tdLength'><?php echo $foodAndBeverageCostTranslate;?></th>
                            <th class='fontStyle expand wrapText tdLength'><?php echo $otherCostTranslate;?></th>
                            <th class='fontStyle expand tdLength actionStyle' colspan='2'><i class='fa fa-cog fa-spin'></i></th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div class="content">
<?php
//// Year and Month Filter ////////////////// -->Head
$sql = "SELECT market_cost_id as mcID, DATE_FORMAT(visit_date, '%W') AS DayOfWeek, DATE_FORMAT(visit_date, '%m/%d/%Y') as ShoppingDate, market_name, food_bev_cost, other_cost 
        FROM market_cost mc
        WHERE user_id = ? 
        AND deleted = 'N' ";

if ($yearSelected == '' && $monthSelected == '')
{
    $sql .= "ORDER BY visit_date DESC";
    $statement = $conn->prepare($sql);
    $statement->bind_param('i', $userId);
} else if ($yearSelected && $monthSelected) {
            $sql .= "AND DATE_FORMAT(visit_date, '%Y') = ?
                    AND DATE_FORMAT(visit_date, '%m') = ?
                    ORDER BY visit_date DESC";
            $statement = $conn->prepare($sql);
            $statement->bind_param('iss', $userId, $yearSelected, $monthSelected);
        } else if ($yearSelected && $monthSelected == '') {
                    $sql .= "AND DATE_FORMAT(visit_date, '%Y') = ?
                            ORDER BY visit_date DESC";
                    $statement = $conn->prepare($sql);
                    $statement->bind_param('is', $userId, $yearSelected);
                } else if ($yearSelected == '' && $monthSelected) {
                            $sql .= "AND DATE_FORMAT(visit_date, '%m') = ?
                                    ORDER BY visit_date DESC";
                            $statement = $conn->prepare($sql);
                            $statement->bind_param('is', $userId, $monthSelected);
                        }
//// Year and Month Filter ////////////////// -->Bottom
$statement->execute();
$result = $statement->get_result();

if($result->num_rows > 0)
{
    echo "<table border='1'>";
        while($row = $result->fetch_assoc())
        {
            $numFBColor = $row['food_bev_cost'] == 0 ? 'silverNumber' : 'blueNumber';
            $numOtherColor = $row['other_cost'] == 0 ? 'silverNumber' : 'blueNumber';
            echo "<tr class='rowHighLight fontStyle' id= " . $row['mcID'] . ">
                    <td class='expand tdLength'>" . $row['DayOfWeek'] . "</td>
                    <td class='expand tdLength'>" . $row['ShoppingDate'] . "</td>
                    <td class='expand tdLength'>" . $row['market_name'] . "</td>
                    <td class='expand tdLength totalCostStyle " . $numFBColor . "'>" . CurrencyFormatter::format($row['food_bev_cost'], $currencyType). "</td>
                    <td class='expand tdLength totalCostStyle " . $numOtherColor . "'>" . CurrencyFormatter::format($row['other_cost'], $currencyType). "</td>
                    <td class='gridTableColor dataEditStyle'>
                            <button id=" ."edit_". $row['mcID'] . " onclick='editClick(".$row['mcID'].", event)' name='edit' class='editSubmitStyle'><i class='fas fa-pen'></i></button>
                    </td>
                    <td class='gridTableColor dataDeleteStyle'>
                            <button id=" ."delete_" . $row['mcID'] . " name='delete' class='deleteSubmitStyle'><i class='fas fa-trash-alt'></i></button>
                    </td>";
        }
    echo "</table>";
}
$statement->close();

?>
            </div>
            <div class="totalSummary">
<?php
//// SUM Filter ////////////-->Header
$sqlTotal = "SELECT SUM(mc.food_bev_cost) AS totalFBCost, SUM(mc.other_cost) AS totalOtherCost 
            FROM market_cost mc
            WHERE user_id = ?
            AND deleted = 'N' ";
if ($yearSelected == '' && $monthSelected == '') {
    // All years and months are selected
    $sqlTotal .= " ORDER BY visit_date DESC";
    $statement = $conn->prepare($sqlTotal);
    $statement->bind_param('i', $userId);
} else if ($yearSelected && ($monthSelected == '')) {
    // Only year is selected
    $sqlTotal .= "AND DATE_FORMAT(visit_date, '%Y') = ?
                    ORDER BY visit_date DESC";
    $statement = $conn->prepare($sqlTotal);
    $statement->bind_param('is', $userId, $yearSelected);
} else if (($yearSelected == '') && $monthSelected) {
    // Only month is selected
    $sqlTotal .= "AND DATE_FORMAT(visit_date, '%m') = ?
                    ORDER BY visit_date DESC";
    $statement = $conn->prepare($sqlTotal);
    $statement->bind_param('is', $userId, $monthSelected);
} else if ($yearSelected && $monthSelected) {
    // Both year and month are selected
    $sqlTotal .= " AND DATE_FORMAT(visit_date, '%Y') = ? 
                   AND DATE_FORMAT(visit_date, '%m') = ?
                   ORDER BY visit_date DESC";
    $statement = $conn->prepare($sqlTotal);
    $statement->bind_param('iss', $userId, $yearSelected, $monthSelected);
}
////SUM Filter////////////-->Bottom  

$statement->execute();
$resultTotal = $statement->get_result();

    // $resultTotal = $conn->query($sqlTotal);

    if($resultTotal->num_rows > 0)
    {
        echo "<table border='1' class='gridTableColor'>";
        while($row = $resultTotal->fetch_assoc())
        {
            $totalFBCost = $row['totalFBCost'];
            $totalOtherCost = $row['totalOtherCost'];
            echo "<tr class='fontStyle'>
                <td class='gridTableColor redundantCol'></td> 
                <td class='gridTableColor totalCostLabel totalCostColor' colspan='2'>" . $totalCostTranslate . "</td> 
                <td class='gridTableColor totalCostStyle totalCostTextStyle totalCostColor'>" . $currencySymbol . " " . CurrencyFormatter::format($row['totalFBCost'], $currencyType) . "</td> 
                <td class='gridTableColor totalCostStyle totalCostTextStyle totalCostColor'>" . $currencySymbol . " " . CurrencyFormatter::format($row['totalOtherCost'], $currencyType) . "</td> 
                <td class='gridTableColor redundantIdStyle dataEditStyle'></td>
                <td class='gridTableColor redundantIdStyle dataDeleteStyle'></td>";
        }
    }
    $budget = 0;
    if (is_null($currentBudget) != 1)
    {
        $budget = $currentBudget;
    }
    $balanceForShopping = $budget - $totalFBCost;
    if ($yearSelected == '' || $monthSelected == '')
    {
        $totalHidden = "hidden";
    }
    echo "<tr class='fontStyle'>
    <td class='gridTableColor redundantCol'></td> 
    <td class='gridTableColor totalCostLabel totalBudgetColor' colspan='2'>" . $totalBudgetTranslate . "</td> 
    <td class='gridTableColor budgetStyle totalCostStyle totalCostTextStyle totalBudgetColor $totalHidden'>" . $currencySymbol . " " . CurrencyFormatter::format($budget, $currencyType) . "</td>
    <td class='gridTableColor redundantIdStyle dataEditStyle' colspan='3'></td>
    </tr>";

    echo "<tr class='fontStyle'>
    <td class='gridTableColor redundantCol'></td> 
    <td class='gridTableColor totalCostLabel totalBalanceColor' colspan='2'>" . $balanceForShoppingTranslate . "</td> 
    <td class='gridTableColor balanceStyle totalCostStyle totalCostTextStyle totalBalanceColor $totalHidden'>" . $currencySymbol . " " . CurrencyFormatter::format($balanceForShopping, $currencyType) . "</td>
    <td class='gridTableColor redundantIdStyle dataEditStyle' colspan='3'></td>
    </tr></tfoot>";      

    echo "</table>";
    $statement->close();
?>
            </div>
        </div>

        <div id="graph">
            <div id="marketVisitedChart" style="width: 100%; height: 300px;"></div>
            <div id="costDistributionChart" style="width:100%; max-width:600px; height:250px;"></div>
        </div>
    </div>
<?php
// Fetch data from SQL for the CHART of VISIT the most
$sql = "SELECT market_name, COUNT(market_name) as total_visits
        FROM market_cost
        WHERE user_id = ?
        AND deleted = 'N'";
//// CHART Filter [The MOST VISITED MARKET CHART]//////-->Header
if (($yearSelected == '') && ($monthSelected == '')) {
    //[All Years + All Months]
    $sql .= " GROUP BY market_name
             ORDER BY total_visits DESC";
    $statement = $conn->prepare($sql);
    $statement->bind_param('i', $userId);
} else if ($yearSelected && ($monthSelected == '')) {
        //[Year selected and All Months]
        $sql .= " AND DATE_FORMAT(visit_date, '%Y') = ?
                GROUP BY market_name
                ORDER BY total_visits DESC";
        $statement = $conn->prepare($sql);
        $statement->bind_param('is', $userId, $yearSelected);
        } else if ($yearSelected == '' && $monthSelected) {
            //[Year selected and All Months]
            $sql .= " AND DATE_FORMAT(visit_date, '%m') = ?
                    GROUP BY market_name
                    ORDER BY total_visits DESC";
            $statement = $conn->prepare($sql);
            $statement->bind_param('is', $userId, $monthSelected);
            } else {
            //[Year selected and month selected]
            $sql .= " AND DATE_FORMAT(visit_date, '%Y') = ?
                    AND DATE_FORMAT(visit_date, '%m') = ?
                    GROUP BY market_name
                    ORDER BY total_visits DESC";
            $statement = $conn->prepare($sql);
            $statement->bind_param('iss', $userId, $yearSelected, $monthSelected);
            }
//// CHART Filter [The MOST VISITED MARKET CHART]//////-->Bottom
$statement->execute();
$result = $statement->get_result();

$chartData = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $chartData[] = [$row['market_name'], (int)$row['total_visits']];
    }
}

// Convert PHP array to JSON
$chartDataJson = json_encode($chartData);
$statement->close();
?>

    <script>
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {

        // Set Data
        const data = google.visualization.arrayToDataTable([
        ['Cost', 'Mhl'],
        ['<?php echo $foodAndBeverageTranslate;?>', <?php echo $totalFBCost ?>],
        ['<?php echo $otherTranslate;?>', <?php echo $totalOtherCost ?>],
        ]);

        // Set Options
        const options = {
            title:'<?php echo $marketCostDistribution;?>',
            is3D:true,
            titleTextStyle: {
                fontSize: 15, // Set the font size (example: 24px)
                bold: true,   // Optional: make the title bold
                color: '#004C99' // Optional: set the title color
            },
            slices: {
                0: { color: 'crimson' },
                1: { color: '#0000CD' }
            }
        };

        // Fetch data from PHP
        const chartData = <?php echo $chartDataJson; ?>;
        // Add headers to the data
        chartData.unshift(['Market', 'Visits']);
        // Set Data
        const dataVisited = google.visualization.arrayToDataTable(chartData);

        const optionVisited = {
            title: '<?php echo $mostVisitedMarkets;?>',
            is3D: true,
            pieHole: 0.4,
            titleTextStyle: {
                fontSize: 15, // Set the font size (example: 24px)
                bold: true,   // Optional: make the title bold
                color: '#004C99' // Optional: set the title color
            },
            slices: {
                0: { color: '#CD3903' }, // Dark Orange fixed
                1: { color: '#01AEAE' }, // Teal
                2: { color: 'darkslateblue' }, // blue
                3: { color: '#00FFFF' }, // Cyan
                4: { color: '32CD32' }, // Green
                5: { color: '#00FF00' }, // Lime
                6: { color: '#F0E68C' }, // beige
                7: { color: '#FF69B4' }, // Violet
                8: { color: '#FFC0CB' }, // Pink
                9: { color: '#FF00FF' }, // Magenta
                10: { color: '#A52A2A' }, // Brown
                11: { color: '#F5F5DC' }, // Beige
                12: { color: '#FFD700' }, // Gold
                13: { color: '#C0C0C0' }, // Silver
                14: { color: '#808080' }, // Gray
                15: { color: '#808000' }, // Olive
                16: { color: '#FF7F50' }, // Coral
                17: { color: '#40E0D0' }, // Turquoise
                18: { color: '#FFFFF0' }, // Ivory
                19: { color: '#F0E68C' }  // Khaki
            }
        };

        // Draw
        const chart = new google.visualization.PieChart(document.getElementById('costDistributionChart'));
        chart.draw(data, options);

        const chartVisited = new google.visualization.PieChart(document.getElementById('marketVisitedChart'));
        chartVisited.draw(dataVisited, optionVisited);
        }
    </script>

    <script src="script.js"></script>
    <button id="scrollToHomeLink" onclick="focusOnHomeLink()"><i class='fas fa-arrow-up'></i></button>
</body>
</html>
<?php
// End output buffering and flush the output
ob_end_flush();
?>
