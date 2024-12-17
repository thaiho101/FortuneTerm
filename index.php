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
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> -->
    <link rel="icon" type="image/x-icon" href="shoppingCart2.png">
</head>
<body>
    <div id="navigation">Market Cost</div>

    <div id="bottom">
        <div id="dataChange">
            <div class="insert">Insert</div>
            <div class="budget">Budget</div>
        </div>

        <div id="dataAnalyze">
            <div class="filter">Filter</div>
            <div class="header">Header</div>
            <div class="content">Content</div>
            <div class="totalSummary">Total</div>
        </div>

        <div id="graph">Graph</div>
    </div>
</body>
</html>
