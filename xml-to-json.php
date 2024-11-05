<?php
// Load the XML file
$xmlFile = 'Varahaldussustem.xml';

// Check if the file exists
if (file_exists($xmlFile)) {
    // Load XML file into a SimpleXMLElement object
    $xml = simplexml_load_file($xmlFile);

    // Convert the SimpleXMLElement object to JSON
    $json = json_encode($xml, JSON_PRETTY_PRINT);

    // Set header to JSON
    header('Content-Type: application/json');

    // Output JSON
    echo $json;
} else {
    // Handle the error if the file does not exist
    echo json_encode(["error" => "File not found"]);
}
