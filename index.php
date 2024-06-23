<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coin Cap</title>
    <link rel="stylesheet" href="syle.css">
</head>
<?php

function fetch($limit, $search) {
    $url = "https://api.coincap.io/v2/assets?limit=" . $limit . "&search=" . $search;
    $response = file_get_contents($url);
    $data = json_decode($response, true);
    return $data;
}

function popularCryptos() {
    $data = fetch(4, "");

    for ($i = 0; $i < count($data['data']); $i += 2) {
        echo '<div class="prow">';
        for ($j = 0; $j < 2; $j++) {
            if ($i + $j < count($data['data'])) {
                $name = $data['data'][$i + $j]['name'];
                $price = $data['data'][$i + $j]['priceUsd'];
                $symbol = $data['data'][$i + $j]['symbol'];
                $link = $data['data'][$i + $j]['explorer'];

                // Format the price to 2 decimal places
                $formattedPrice = number_format($price, 2);

                echo '<a href="' . $link . '" target="_blank">';
                echo '<div class="p">';
                echo '<strong>' . $name . ' (' . $symbol . ')</strong>';
                echo ' ~ ' . $formattedPrice . ' USD';
                echo '</div>';
                echo '</a>';
            }
        }
        echo '</div>';
    }
}
$limit = 10;
$search = "";
if (isset($_GET['search']) && $_GET['search'] !== '') {
    $search = $_GET['search'];
    $limit = '';
}
else {
    $limit = 10;
}
function market($limit, $search) {
    $data = fetch($limit, $search);
    
    echo '<table class="m">';
    for ($i = 0; $i < count($data['data']); $i++) {
        $name = $data['data'][$i]['name'];
        $price = $data['data'][$i]['priceUsd'];
        $symbol = $data['data'][$i]['symbol'];
        $link = $data['data'][$i]['explorer'];
        $change = $data['data'][$i]['changePercent24Hr'];

        // Format the price to 2 decimal places
        $formattedPrice = number_format($price, 2);
        $formattedChange = number_format($change, 2);

        // Set changePercent24Hr color
        if ($change > 0) {
            $formattedChange = '<span style="color: #345a00">' . $formattedChange . '</span>';
        } else if ($change < 0) {
            $formattedChange = '<span style="color: red">' . $formattedChange . '</span>';
        }

        // Add the data to the table
        echo '<tr>';
        echo '<td><strong>' . $name . ' (' . $symbol . ')</strong></td>';
        echo '<td>' . $formattedPrice . ' USD</td>';
        echo '<td class="change"> 24h: ' . $formattedChange . ' %</td>';
        echo '<td class="explore"><a href="' . $link . '" target="_blank"><strong>EXPLORER</strong></a></td>';
        echo '</tr>';
    }
    echo '</table>';
}
?>

<body>
    <div class="header">
        <img class="logo" src="img/logo.png" alt="CoinMarketCap">
    </div>
    <div class="container">
        <div class="up">
            <div class="info">
                <p class="title">This is a project for fetching data from <a href="https://docs.coincap.io/">CoinCap API</a>.</p>
                <p class="creator">Created by <a href="https://github.com/Negarebr">Negar Ebrahimi</a></p>
            </div>
            <div class="popular">
                <h4>Popular Cryptocurrencies</h4>
                <div class="popularcoins">
                    <?php popularCryptos(); ?>
                </div>
            </div>
        </div>
        <div class="down">
            <div class="cmd">
                <div class="market-title">
                    <h4>Market</h4>
                </div>
                <div class="search">
                    <div class="search-text">
                        <p> Search Cryptocurrency by Name or Symbol: </p>
                    </div>
                    <div class="search-input">
                        <form action="#" method="get">
                            <input class="input" type="text" id="search" name="search" placeholder="Search..." value="<?php echo $search; ?>">
                            <input class="submit" type="submit" value="Search" name="submit">
                        </form>
                    </div>
                </div>
            </div>
            <div class="market">
                <?php market($limit, $search); ?>
            </div>
        </div>
    </div>
</body>
</html>