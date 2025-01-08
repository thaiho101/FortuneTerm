<?php
// Function to get location data from ip-api
function getCountryByIP($ip_address) {
    // API URL with the provided IP address
    $url = "http://ip-api.com/json/{$ip_address}";

    // Use file_get_contents to send GET request
    $response = file_get_contents($url);

    // Decode the JSON response
    $data = json_decode($response, true);

    // Check for a valid response
    if ($data && $data['status'] === 'success') {
        return [
            'country' => $data['country'], // Full country name
            'city' => $data['city'], // City
            'region' => $data['regionName'], // Region name
            'zip' => $data['zip'], // Zip
            'timezone' => $data['timezone'], // Timezone
            'org' => $data['org'], // Org
        ];
    }

    // Return error if API fails
    return ['error' => 'Unable to fetch data'];
}

// Input IP address
$ip_address = '72.182.175.111'; // Replace with dynamic input if needed

// Call the function and get location data
$result = getCountryByIP($ip_address);

// Display the result
if (isset($result['error'])) {
    echo "Error: " . $result['error'];
} else {
    echo "Country: " . $result['country'] . "\n";
    echo "City: " . $result['city'] . "\n";
    echo "Region: " . $result['region'] . "\n";
    echo "Zip: " . $result['zip'] . "\n";
    echo "Timezone: " . $result['timezone'] . "\n";
    echo "Organize: " . $result['org'] . "\n";
}
?>
