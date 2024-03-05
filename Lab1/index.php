<?php
ini_set("memory_limit", "-1");

// Assuming city.list.json is in the same directory as this PHP file
$string = file_get_contents("city.list.json");

// Assuming city.list.json is an array of cities
$json_cities = array_filter(json_decode($string, true), fn($city) => $city["country"] == "EG");

if (isset($_POST["submit"])) {
    $apiKey = "ab88e9304267befb6062a70e4ebda67c";
    $city_id = $_POST["city"]; // Using city ID instead of name for accuracy

    // Construct the API URL using the selected city ID
    $ApiUrl = "https://api.openweathermap.org/data/2.5/weather?id=" . urlencode($city_id) . "&appid=" . $apiKey;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $ApiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    $response = curl_exec($ch);

    curl_close($ch);
    $data = json_decode($response);

    // Display the weather information
    if ($data !== null && isset($data->main, $data->weather[0], $data->dt)) {
        $temperature = $data->main->temp;
        $humidity = $data->main->humidity;
        $description = $data->weather[0]->description;
        $city = $data->name;

        // Get the weather icon code from the OpenWeatherMap response
        $iconCode = $data->weather[0]->icon;

        // Construct the URL for the weather icon
        $iconUrl = "http://openweathermap.org/img/w/{$iconCode}.png";

        // Convert timestamp to human-readable date and time
        $time = date("Y-m-d H:i:s", $data->dt);

        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Weather App</title>
            <link rel="stylesheet" href="style.css">
        </head>
        <body>
            <div class="container">
            <h1 style="text-align: center;"><?php echo "City: " . $city . "<br>"; ?></h1>
            <img src="<?php echo $iconUrl; ?>" alt="Weather Icon" style="display: block; margin: 0 auto;">
            <h2 style="text-align: center;"><?php echo "Time: " . $time . "<br>"; ?></h3>
            <h3 style="text-align: center;"><?php echo "Temperature: " . $temperature . " K<br>"; ?></h3>
            <h3 style="text-align: center;"><?php echo "Humidity: " . $humidity . "%<br>"; ?></h3>
            <h3 style="text-align: center;"><?php echo "Description: " . $description . "<br>"; ?></h3>
           
           
            </div>
        </body>
        </html>
    <?php
    } else {
        echo "Error fetching weather data or invalid city.";
    }
} else {
    // Display form with dropdown list of cities
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Weather App</title>
    </head>
    <body>
        <form method="POST" action="">
            <label for="city">Select a city:</label>
            <select id="city" name="city" required>
                <?php
                foreach ($json_cities as $city) {
                    echo "<option value='{$city["id"]}'>{$city["name"]}</option>";
                }
                ?>
            </select>
            <input type="submit" name="submit" value="Get Weather">
        </form>
    </body>
    </html>
<?php
}
?>
