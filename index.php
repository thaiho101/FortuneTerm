<?php
// Set Cache-Control to no-cache, no-store, must-revalidate
header("Cache-Control: no-cache, no-store, must-revalidate");

// Set Pragma to no-cache (for HTTP/1.0 backward compatibility)
header("Pragma: no-cache");

// Set Expires to a past date to invalidate the cache
header("Expires: 0");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Market Cost</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="shoppingCart2.png">
</head>
<body>
    <div id="navigation">Market Cost</div>

    <div id="bottom">
        <div id="dataChange">
            <div class="insert">Insert
                <form method='post'>
                    <div class='form-row'>
                        <label for='date' type='text' class='insertLabel'>Date</label>
                        <input type='date' name='date' id='date' class='insertBox'></input>
                    </div>

                    <div class='form-row'>
                        <label for='store' type='text' class='insertLabel'>Market</label>
                        <input type='text' name='store' id='store' class='insertBox'></input>
                    </div>

                    <div class='form-row'>
                        <label for='fbCost' type='text' class='insertLabel'>Food & Beverage Cost</label>
                        <input type='number' name='fbCost' id='fbCost' class='insertBox'></input>
                    </div>

                    <div class='form-row'>
                        <label for='otherCost' type='text' class='insertLabel'>Other Cost</label>
                        <input type='number' name='otherCost' id='otherCost' class='insertBox'></input>
                    </div>

                    <div>
                        <button type='submit' name='insert' class='button'>Insert</button>
                    </div>
                </form>
            </div>
            <div class="budget">Budget</div>
        </div>

        <div id="dataAnalyze">
            <div class="filter">
                <form method='get' class='yearMonthDiv'>
                    <label for="year" class='yearMonthFilterStyle'><i class="fa fa-cog fa-spin"></i> Year:  </label>
                        <select name="year" class='yearOptionStyle'id='year'>
                                <option value=""> All Years </option>
                                <option value="2024">2024</option>
                                <option value="2023">2023</option>
                                <option value="2022">2022</option>
                                <option value="2021">2021</option>
                                <option value="2020">2020</option>
                                <option value="2019">2019</option>
                        </select>
                    <label for="month" class='yearMonthFilterStyle'><i class="fa fa-cog fa-spin"></i> Month: </label>
                        <select name="month" class='monthOptionStyle' id='month'>
                                <option value=""> -- All Month -- </option>
                                <option value="01">01</option>
                                <option value="02">02</option>
                                <option value="03">03</option>
                                <option value="04">04</option>
                                <option value="05">05</option>
                                <option value="06">06</option>
                                <option value="07">07</option>
                                <option value="08">08</option>
                                <option value="09">09</option>
                                <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12">12</option>
                        </select>
                        <input type="submit" value="Filter" class='monthSubmitStyle'>
                </form>
            </div>
            <div class="header">
                <table class='headerTable'>
                    <thead>
                        <tr class='headerRow'>
                            <th class='expand wrapText tdLength'>Day of     Week</th>
                            <th class='expand tdLength'>Date</th>
                            <th class='expand tdLength'>Store</th>
                            <th class='expand wrapText tdLength'>Food & Beverage Cost</th>
                            <th class='expand wrapText tdLength'>Other Cost</th>
                            <th class='expand tdLength actionStyle' colspan='2'><i class='fa fa-cog fa-spin'></i></th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div class="content">
<?php
require_once("config.php"); 
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
// $conn = new mysqli("localhost", "root", "", "fortuneterm_local");

if ($conn->connect_error)
{
        die("Connect failed: " . $conn->connect_error);
}
$sql = "SELECT DATE_FORMAT(visit_date, '%W') AS DayOfWeek, DATE_FORMAT(visit_date, '%m/%d/%Y') as ShoppingDate, m.name, food_bev_cost, other_cost FROM market_cost mc
JOIN market m ON mc.market_id = m.market_id WHERE user_id = 1";
$result = $conn->query($sql);
if($result->num_rows > 0)
{
    echo "<table border='1'>";
        while($row = $result->fetch_assoc())
        {
            echo "<tr>
                    <td class='expand tdLength'>" . $row['DayOfWeek'] . "</td>
                    <td class='expand tdLength'>" . $row['ShoppingDate'] . "</td>
                    <td class='expand tdLength'>" . $row['name'] . "</td>
                    <td class='expand tdLength'>" . $row['food_bev_cost']. "</td>
                    <td class='expand tdLength'>" . $row['other_cost']. "</td>
                    <td class='dataEditStyle'>
                        <form method='post' style='display:inline;'>
                            <input type='hidden' name='edit_id' value='" . $row['Id'] . "'>
                            <button type='submit' name='edit' class='editSubmitStyle'><i class='fas fa-pen'></i></button>
                        </form>
                    </td>
                    <td class='dataDeleteStyle'>
                        <form method='post' style='display:inline;'>
                            <input type='hidden' name='id' value='" . $row['Id'] . "'>
                            <button type='submit' name='delete' class='deleteSubmitStyle'><i class='fas fa-trash-alt'></i></button>
                        </form>
                    </td>";
        }
    echo "</table>";
}

?>
            </div>
            <div class="totalSummary">Total</div>
        </div>

        <div id="graph">Graph</div>
    </div>
</body>
</html>
