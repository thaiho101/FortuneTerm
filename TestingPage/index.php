<?php 
/////////////// Get data from IP-API -->Header ///////////
// Function to get location data from ip-api
$user_ip1 = "2607:fb91:9af:4a34:463:edfe:165a:b902";
function getCountryByIP($ip_address) {
    // API URL with the provided IP address
    $url = "http://ip-api.com/json/{$user_ip1}";

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
$result = getCountryByIP($ip_address);
                        $country = $result['country'];
                        $city = $result['city'];
                        $region = $result['region'];
                        $zip = $result['zip'];
                        $timeZone = $result['timezone'];
                        $organize = $result['org'];

echo $country;
echo $city;
echo $region;
echo $zip;
echo $timeZone;
echo $organize;
?>