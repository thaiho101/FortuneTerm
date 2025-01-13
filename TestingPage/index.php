<?php
// Translation API URL for LibreTranslate
$apiUrl = "https://libretranslate.de/translate";

// Data for POST request
$data = [
    'q' => 'Hello, world!', // Text to translate
    'source' => 'en',       // Source language code
    'target' => 'vi',       // Target language code
    'format' => 'text'
];

// Use cURL to make the POST request
$ch = curl_init($apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/x-www-form-urlencoded'
]);

// Execute the request and fetch the response
$response = curl_exec($ch);
curl_close($ch);

// Decode JSON response
$result = json_decode($response, true);

// Display the translation
if (isset($result['translatedText'])) {
    echo "Original text: Hello, world!\n";
    echo "Translated text: " . $result['translatedText'] . "\n";
} else {
    echo "Error: Translation failed. Response: " . json_encode($result);
}
?>
